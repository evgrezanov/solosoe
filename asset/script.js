jQuery( document ).ready( function( $ ) {
    // url for request Solr
    var solrUrl = window['solrUrl'];

    // return name_code from solr
    var display_product = function( product ) {
        return product.name_code;
    }

    // transform solr search result
    var transform_products = function( products ) {
        console.log(products);
        return $.map( products, function( product ) {
            return {
                id: product.response.docs.id,
                name_code: product.response.docs.name_code,
                prd_id: product.response.docs.prd_id,
                score: product.response.docs.score
            };
        } );
    };

    // Bloodhound configuration 
    // https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md
    var datasets = [
        {
            name: 'products',
            source: new Bloodhound( {
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name_code'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                identify: function( product ) {
                    return product.id;
                },
                sufficient: 1,
                remote: {
                    url: 'http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&qf=name_code&q=%QUERY&wt=json',
                    wildcard: '%QUERY',
                    //transform: transform_products,
                    prepare: function(query, settings) {
                        settings.beforeSend = function(jqXHR, settings) {
                            settings.xhrFields = { withCredentials: true };
                        };
                        settings.crossDomain = true;
                        settings.dataType = "jsonp";
                        settings.url = settings.url.replace('%QUERY', query);
                        //console.log(settings);
                        return settings;
                    },
                    transform: function (data) { 
                        //response = data.response.docs;
                        response = JSON.stringify(data.response.docs);         
                        var newData = [];               
                        data.forEach(function (item) {
                            newData.push({'name': item});
                        });
                        console.log(newData);
                        return response;
                    }
                },
                //indexRemote: true
            } ),
            display: display_product
        }
    ];

    // init typeahead
    $( '#solr-typeahead' ).typeahead( {
        minLength: 3,
        highlight: true,
        hint: true,
        displayKey: 'name_code',
        templates: {
            empty: [
                '<div class="noitems">',
                'No Items Found',
                '</div>'
            ].join('\n')
        }
    }, datasets);

} );