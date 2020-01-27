jQuery( document ).ready( function( $ ) {
    // url for request Solr
    var solrUrl = window['solrUrl'];

    // return name_code from solr
    var display_product = function( product ) {
        return product.name_code;
    }

    // transform solr search result
    var transform_products = function( data ) {
        var docs = JSON.stringify(data.response.docs);
        var products = JSON.parse(docs);
        console.log(products);
        return $.map( products, function( product ) {
            return {
                id: product.id,
                name_code: product.name_code,
                prd_id: product.prd_id,
                score: product.score
            };
        } );
    };

    // Bloodhound configuration 
    var datasets = [
        {
            name: 'products',
            source: new Bloodhound( {
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace([ 'name_code', 'prd_id' ]),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                identify: function( product ) {
                    return product.id;
                },
                sufficient: 1,
                remote: {
                    url: 'http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&qf=name_code&q=%QUERY',
                    wildcard: '%QUERY',
                    prepare: function(query, settings) {
                        settings.beforeSend = function(jqXHR, settings) {
                            settings.xhrFields = { withCredentials: true };
                        };
                        settings.crossDomain = true;
                        settings.dataType = "jsonp";
                        settings.jsonp = 'json.wrf';
                        settings.url = settings.url.replace('%QUERY', query);
                        console.log(settings.url);
                        return settings;
                    },
                    transform: transform_products,
                },
                indexRemote: true
            } ),
            limit: 8,
            display: display_product,
        }
    ];

    // init typeahead
    $( '#solr-typeahead' ).typeahead( {
        minLength: 3,
        highlight: true,
        hint: true,
        templates: {
            empty: [
              '<div class="empty-message">',
                'unable to find any products that match the current query',
              '</div>'
            ].join('\n'),
            suggestion: function (data) {
                return '<p><strong>' + product.name_code + '</strong> - ' + product.prd_id + '</p>';
            }
        }
    }, datasets);

} );