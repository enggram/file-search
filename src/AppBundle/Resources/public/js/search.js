$(document).ready(function () {
    $('#searchInput').typeahead({
        source: function(query, process) {
            var newData = [];
            return $.ajax({
                url: Routing.generate('search_result_json'),
                type: 'get',
                data: {keyword: query},
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(){
                        newData.push(this.keyword+'('+this.filename+')');
                    });
                    return process(newData);
                }
            });
        },
        onSelect: function (obj) {
            alert('Selected ');
        }
    });
});