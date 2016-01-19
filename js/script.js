window.onload = function () {
    if (document.forms['logged'] != undefined) {
        document.forms['logged'].elements['lb'].onclick = function () {
            window.open('./leaderboard', '_blank');
        };
    }

    if (document.forms['navigate'] != undefined) {
        document.forms['navigate'].elements['previous'].onclick = function () {
            window.close();
        };
    }
};
