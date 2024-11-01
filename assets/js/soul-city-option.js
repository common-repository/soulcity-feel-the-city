/**
 * Created by gduvaux on 02/02/2016.
 */

(function($){

    var countVote, votePerMood, dataCompose = [];

    $.ajax({
        url: '//http://soul-city.dev/app_dev.php/survey/site/' + blogName,
        success: function(data){
            countVote = data.count_vote;
            votePerMood = data.vote_per_mood;

            //console.log(countVote);

            countVote.forEach(function(element, index, array){
                //console.log(element);
                var tempObj = {
                    nomArticle: '',
                    nbVote: 0,
                    votePerMood: []
                };
                tempObj.nomArticle = element.postTitle;
                tempObj.nbVote = element[1];

                dataCompose.push(tempObj);
            });

            votePerMood.forEach(function(mood, index, array){
                //console.log(element);
                var tempMood = {
                    name: '',
                    nbVoteMood: 0
                };

                tempMood.name = mood.name;
                tempMood.nbVoteMood = mood[1];

                dataCompose.forEach(function(element, index, array){
                    if(element.nomArticle == mood.postTitle){
                        //console.log(index + ' plouf');
                        array[index].votePerMood.push(tempMood);
                    }
                });
            });

            var $tabStats = $('.table-stats tbody');

            dataCompose.forEach(function(element, index, array){
                var row = '';
                row = row.concat('<tr><td>', element.nomArticle, '</td>');
                row = row.concat('<td>', element.nbVote, '</td>');

                var texteMood = '';
                element.votePerMood.forEach(function(element, index, array){
                    texteMood = texteMood.concat(element.name, ' : ', element.nbVoteMood, ' ');
                });
                row = row.concat('<td>', texteMood, '</td>');

                row = row.concat('</tr>');

                $tabStats.append(row);
            });

            //console.log(dataCompose);
        },
        dataType: 'json'
    });

})(jQuery);
