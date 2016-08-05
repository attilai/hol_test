jQuery(function(){

    jQuery.getJSON('/js/ekomi/data/data.json', function(response) {
        var ratingData          = response['ratingData'];
        var reviewData          = response['reviewData'];
        var $seal               = jQuery('.ekomi-widget__rating__seal');
        var randomReviewData    = [];
        var displayedReviews    = jQuery('.ekomi-widget__reviews').children();
        var seals               = {
            standart:   '/js/ekomi/img/standart.png',
            bronze:     '/js/ekomi/img/bronze.png',
            silver:     '/js/ekomi/img/silber.png',
            gold:       '/js/ekomi/img/gold.png'
        };

        // redirect to certificate page if widget is clicked and change the cursor to pointer when hovering over it
        jQuery('.ekomi-widget')
            .click(function() {
                window.location.href='https://www.ekomi.nl/klantenmeningen-hollandgoldnl.html';
            })
            .hover(function() {
                jQuery(this).css({
                    cursor: 'pointer'
                });
            });

        // display rating average
        jQuery('.ekomi-widget__rating__score').text(ratingData[0] * 2 + '/10');
		
        //display average rating stars
        jQuery('.ekomi-widget__rating .ekomi-widget__stars__filling').css({width: (ratingData[0] * 20) + '%'});

        // display correct seal
        if(ratingData[0] < 4.0) {
            $seal.attr('src', seals.standart);
        } else if(ratingData[0] < 4.4) {
            $seal.attr('src', seals.bronze);
        } else if(ratingData[0] < 4.8) {
            $seal.attr('src', seals.silver);
        } else {
            $seal.attr('src', seals.gold);
        }

        // fill widget with random reviews and according stars
        var counter = 0;
        jQuery.each(displayedReviews, function(key, reviewContainer){
            var randomNumber = Math.floor(Math.random() * reviewData.length);
            randomReviewData.push(reviewData[randomNumber]);
            reviewData.splice(randomNumber, 1);
            jQuery(reviewContainer)
                .children().first().text('"' + randomReviewData[counter]['review'] + '"')
                .next().children().css({width: (randomReviewData[counter]['rating'] * 20) + '%'} );
            counter++;
        });
    });
});