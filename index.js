const config = require('./config.json');
const db = require("./database");
const viz = require("viz-js-lib");
viz.config.set("websocket", "wss://viz.lexai.host/");

async function runActions() {
let result_create = await db.createTables();
console.log(result_create);
}

async function processCustom(timestamp, custom_body) {
let json = JSON.parse(custom_body.json);
const table_id = json.table;
const data = json.data;
let approve = false;
if (table_id === 'assessments') {
let lesson = await db.getData('lessons', 'name', data.lesson);
let disciple = await db.getData('disciples', 'login', data.disciple);
if (lesson.length > 0 && disciple.length > 0) {
approve = true;
}
} else if (table_id === 'lactors') {
approve = true;
} else if (table_id === 'disciples') {
approve = true;
} else if (table_id === 'lesson_topics') {
let lesson = await db.getData('lessons', 'name', data.lesson);
if (lesson.length > 0) {
approve = true;
}
} else if (table_id === 'lessons') {
let lactors = data.lactors.split(',');
let lactors_count = 0;
let all_lactors = lactors.length;
for (let lactor of lactors) {
let is_lactor = await db.getData("lactors", "login", lactor);
if (is_lactor.length > 0) {
lactors_count += 1;
}
}
if (lactors_count === all_lactors) {
approve = true;
}
}
console.log('approve: ' + approve);
let checking_data = await db.checkData(table_id, data);
console.log(JSON.stringify(checking_data));
if (checking_data.length === 0) {
let lactors = await db.getData("lactors", "login", custom_body.required_regular_auths[0]);
if (lactors.length > 0 && lactors[0].login === custom_body.required_regular_auths[0] && table_id === 'assessments' || table_id === 'lesson_topics' && approve === true) {
let res = await db.addData(table_id, data);
} else if (custom_body.required_regular_auths[0] === config.metodist && approve === true) {
let res = await db.addData(table_id, data);
				}
}
}

async function processBlock(bn) {
    const block = await viz.api.getBlockAsync(bn);
    for(let tr of block.transactions) {
        for(let operation of tr.operations) {
            const [op, opbody] = operation;
            switch(op) {
                case "custom":
    if(opbody.id === config.id) {
						await processCustom(block.timestamp, opbody);
						}
										break;
                default:
                    //неизвестная команда
            }
        }
    }
}



let bn = config.block;
async function run() {
    const props = await viz.api.getDynamicGlobalPropertiesAsync();
    bn = bn || props.last_irreversible_block_num;
    for(; bn < props.last_irreversible_block_num; bn++){
        console.log('Обработка блока' + bn);
        await processBlock(bn);
    }
}

runActions();
setInterval(() => run(), 3000);