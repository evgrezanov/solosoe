jQuery( document ).ready( function( $ ) {
    // url for request Solr
    var solosoeParams = window['solrUrl'];
    solrUrl = solosoeParams['solr_url'];
    siteUrl = solosoeParams['site_url'];
    
    // return name_code from solr
    var display_product = function( product ) {
        return product.name_code;
    }

    var one_result;

    // transform solr search result
    var transform_products = function( data ) {
        var docs = JSON.stringify(data.response.docs);
        var products = JSON.parse(docs);
        one_result = products.length;
        console.log(one_result);
        if (one_result == 1) {
            console.log(products);
            var prod_id = products[0].name_code;
            console.log(prod_id);
            if (prod_id.length >= 6 && prod_id.length <= 11) {
                console.log(siteUrl + products[0].prd_id);
                window.location.href = siteUrl + products[0].prd_id;
            }
        } else {
            return $.map( products, function( product ) {
                return {
                    id: product.id,
                    name_code: product.name_code,
                    prd_id: product.prd_id,
                    score: product.score,
                    prod_url: siteUrl + product.prd_id,
                };
            } );
        }    
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
                            settings.xhrFields = { 
                                withCredentials: true 
                            };
                        };
                        settings.crossDomain = true;
                        settings.dataType = "jsonp";
                        settings.jsonp = 'json.wrf';
                        settings.url = settings.url.replace('%QUERY', query);
                        settings.url = settings.url.replace(/ /g, '%20');
                        console.log(settings.url);
                        return settings;
                    },
                    transform: transform_products,
                },
                indexRemote: true
            } ),
            limit: 5,
            display: display_product,
        }
    ];
    
    // init typeahead
    $( '#solr-typeahead' ).typeahead( {
        minLength: 3,
        highlight: true,
        hint: true,
        autoselect: true,
        templates: {
            suggestion: function (data) {
                return '<p><strong>' + product.name_code + '</strong></p>';
            },
        }
    }, datasets)
    .on('typeahead:asyncrequest', function() {
        $('.Typeahead-spinner').show();
    })
    .on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('.Typeahead-spinner').hide();
    });

    // redirect to product page
    $('#solr-typeahead').bind('typeahead:select', function(ev, suggestion) {
        console.log('Selection: ' + suggestion.prod_url);
        window.location.href = suggestion.prod_url;
    });
    
    // redirect for barcode scanner
    $('#solr-typeahead').bind('typeahead:render', function(ev, suggestions, flag, datasets) {
        $('#solr-typeahead').parent().find('.tt-selectable:first').addClass('tt-cursor');
    });

});