/*** add active class and stay opened when selected ***/
var prefix = [];
var url = window.location;
var segment = url.href.split(/[?#]/).shift().match(/\/[^/]+?/g).length;

for (let index = 1; index < segment; index++) {
    prefix.push(url.pathname.split('/')[index]);
}

// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
    if (this.href) {
        var condition = [];
        let checker = arr => arr.every(Boolean);

        for (let index = 0; index < prefix.length; index++) {
            condition.push(prefix[index].indexOf(this.href.split('/')[index + 3]) == 0);
        }

        return this.href == url || url.href.indexOf(this.href) == 0 || checker(condition);
    }
}).addClass('active');

// for the treeview
$('ul.nav-treeview a').filter(function() {
    if (this.href) {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');