function checkWorkingNode() {
    const NODES = [
        "wss://solox.world/ws",
        "wss://vizlite.lexai.host/",
    ];
    let node = localStorage.getItem("node") || NODES[0];
    const idx = Math.max(NODES.indexOf(node), 0);
    let checked = 0;
    const find = (idx) => {
        if (idx >= NODES.length) {
            idx = 0;
        }
        if (checked >= NODES.length) {
            alert("no working nodes found");
            return;
        }
        node = NODES[idx];
        console.log("check", idx, node);
        viz.config.set("websocket", node);
        try {
            viz.api.stop();
        } catch(e) {
        }
        
        let timeout = false;
        let timer = setTimeout(() => {
            console.log("timeout", NODES[idx])
            timeout = true;
            find(idx + 1);
        }, 3000);
        viz.api.getDynamicGlobalPropertiesAsync()
            .then(props => {
                if(!timeout) {
                    check = props.head_block_number;
                    console.log("found working node", node);
                    localStorage.setItem("node", node);
                    clearTimeout(timer);
                }
            })
            .catch(e => {
                console.log("connection error", node, e);
                find(idx + 1);
            });
    }
    find(idx);
}
checkWorkingNode();

var viz_login = '';
var posting_key = '';

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {        
        vars[key] = value;
    }); 

    return vars;
}

async function ajaxSend(login) {

	if (getUrlVars()) {
		var data = getUrlVars();

        page = data['page'].toLowerCase();

        action   = (data['action'] !== undefined) ? action = "&action=" + data["action"] : '';        
        lactor   = (data['lactor'] !== undefined) ? lactor = "&lactor=" + data["lactor"] : '';        
        disciple = (data['disciple'] !== undefined) ? disciple = "&disciple=" + data["disciple"] : '';        
        lesson   = (data['lesson'] !== undefined) ? lesson = "&lesson=" + data["lesson"] : '';        

        $(document).ready(function() {            
            $(".content").load("op.php", "user=" + login + "&page=" + page + action + lactor + disciple + lesson);
        });    
    
    }

}

async function userAuth() {
			let login = $('#this_login').val();
			let posting = $('#this_posting').val();
			if (localStorage.getItem('PostingKey')) {
		var isPostingKey = sjcl.decrypt(login + '_postingKey', localStorage.getItem('PostingKey'));
			} else if (sessionStorage.getItem('PostingKey')) {
				var isPostingKey = sjcl.decrypt(login + '_postingKey', sessionStorage.getItem('PostingKey'));
	} else {
var isPostingKey = posting;
}

			var resultIsPostingWif = viz.auth.isWif(isPostingKey);

			if (resultIsPostingWif === true) {
const account_approve = await viz.api.getAccountsAsync([login]);
const public_wif = viz.auth.wifToPublic(isPostingKey);
let posting_public_keys = [];
if (account_approve.length > 0) {
for (key of account_approve[0].regular_authority.key_auths) {
posting_public_keys.push(key[0]);
}
} else {
window.alert('Вероятно, аккаунт не существует. Просьба проверить введённый логин.');
}
if (posting_public_keys.includes(public_wif)) {
	var isSavePosting = document.getElementById('isSavePosting');
	if (isSavePosting.checked) {
localStorage.setItem('login', login);
	localStorage.setItem('PostingKey', sjcl.encrypt(login + '_postingKey', posting));
	} else {
		sessionStorage.setItem('login', login);
		sessionStorage.setItem('PostingKey', sjcl.encrypt(login + '_postingKey', posting));
	}

	viz_login = login;
			posting_key = isPostingKey;
} else if (account_approve.length === 0) {
window.alert('Аккаунт не существует. Пожалуйста, проверьте его');
} else {
	window.alert('Постинг ключ не соответствует пренадлежащему аккаунту.');
}
		} else {
window.alert('Постинг ключ имеет неверный формат. Пожалуйста, попробуйте ещё раз.');
}

if (!viz_login && !posting_key) {
		$('#delete_posting_key').css("display", "none");
		$('#unblock_form').css("display", "block");
} else {
		$('#unblock_form').css("display", "none");
	$('#delete_posting_key').css("display", "block");
	jQuery("#delete_posting_key").html('<p align="center"><a onclick="localStorage.removeItem(\'login\'\); localStorage.removeItem(\'PostingKey\'\);     location.reload();">Выйти</a></p>');
	ajaxSend(localStorage.getItem('login'));
}
			} // end userAuth