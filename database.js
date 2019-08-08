const config = require('./config.json');
const mysql = require('mysql');
let connection = mysql.createPool({
  host     : config.db_host,
  user     : config.db_login,
  password : config.db_password,
  database : config.db_name,
  connectionLimit: 30
});


  function addData(table_id, data) {
    return new Promise((resolve, reject) => {
        connection.query('insert INTO ' + table_id + ' SET ?', data, (error, result, fields) => {
            if(error) {
                      reject(error);
               } else {
                resolve(result);
}
        });
});
}

function checkData(table_id, data) {
   let sql = 'SELECT * FROM '+table_id+' WHERE ';
for (let entry in data) {
   return new Promise((resolve, reject) => {
    connection.query(sql+entry+" = '"+data[entry]+"'", (error, result) => {
               if(error) {
               console.log("ОШИБКА ПРИ ВЫПОЛНЕНИИ ЗАПРОСА: "+sql+data[entry]);
                         reject(error);
               } else {
                   resolve(result);
   }
   });
     });
   }   
   }

	function getData(table_id, filde, value) {
    return new Promise((resolve, reject) => {
        connection.query('SELECT * FROM '+table_id+' WHERE '+filde+' = "'+value+'";', (error, results, fields) => {
            if(error) {
                      reject(error);
               } else {
                resolve(results);
}
});            
});
	}

function createTables() {
 var tables = [
 "lactors (id integer NOT NULL AUTO_INCREMENT, login text, PRIMARY KEY(id))",
"disciples (id integer NOT NULL AUTO_INCREMENT, login text, PRIMARY KEY(id))",
	"lessons (id integer NOT NULL AUTO_INCREMENT, name text, lactors text, PRIMARY KEY(id))",
	"lesson_topics (id integer NOT NULL AUTO_INCREMENT, date date, lesson text, topics text, PRIMARY KEY(id))",
	"assessments (id integer NOT NULL AUTO_INCREMENT, date date, lesson text, disciple text, assessment integer, PRIMARY KEY(id))"
];	
let sql = 'CREATE TABLE IF NOT EXISTS ';


tables.forEach(function(entry, index){
	
 return new Promise((resolve, reject) => {
connection.query(sql+entry, (error, result) => {
            if(error) {
			console.log("ОШИБКА ПРИ ВЫПОЛНЕНИИ ЗАПРОСА: "+sql+entry);
                      reject(error);
			} else {
                resolve(result);
}
});
  });
});   
}

module.exports.addData = addData;
module.exports.checkData = checkData;
module.exports.getData = getData;
module.exports.createTables = createTables;