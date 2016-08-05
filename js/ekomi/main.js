jQuery(function() {
    jQuery.getJSON("/js/ekomi/data/data.json", function(e) {
        var t = e.ratingData
          , i = e.reviewData
          , r = jQuery(".ekomi-widget__rating__seal")
          , n = []
          , o = jQuery(".ekomi-widget__reviews").children()
          , a = {
            standart: "/js/ekomi/img/standart.png",
            bronze: "/js/ekomi/img/bronze.png",
            silver: "/js/ekomi/img/silber.png",
            gold: "/js/ekomi/img/gold.png"
        };
        jQuery(".ekomi-widget").click(function() {
            window.open('https://www.ekomi.nl/klantenmeningen-hollandgoldnl.html','_blank'); 
            //window.location.href = "https://www.ekomi.nl/klantenmeningen-hollandgoldnl.html"
   
        }).hover(function() {
            jQuery(this).css({
                cursor: "pointer"
            })
        }),
        jQuery(".ekomi-widget__rating__score").text(2 * t[0] + "/10"),
        jQuery(".ekomi-widget__rating .ekomi-widget__stars__filling").css({
            width: 20 * t[0] + "%"
        }),
        t[0] < 4 ? r.attr("src", a.standart) : t[0] < 4.4 ? r.attr("src", a.bronze) : t[0] < 4.8 ? r.attr("src", a.silver) : r.attr("src", a.gold);
        var s = 0;
        jQuery.each(o, function(e, t) {
            var r = Math.floor(Math.random() * i.length);
            n.push(i[r]),
            i.splice(r, 1),
            jQuery(t).children().first().text('"' + n[s].review + '"').next().children().css({
                width: 20 * n[s].rating + "%"
            }),
            s++
        })
    })
});
