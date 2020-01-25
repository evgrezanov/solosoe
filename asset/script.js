jQuery( document ).ready( function( $ ) {

    // url for request Solr
    var solrUrl = window['solrUrl'];

    // return name_code from solr
    var display_product = function( product ) {
        return product.name_code;
    }

    // transform solr search result
    var transform_products = function( products ) {
        return $.map( products, function( product ) {
            return {
                id: product.id,
                name_code: product.name_code,
                prd_id: product.prd_id,
                _version_: product._version_,
                score: product.score
            };
        } );
    };

    // Bloodhound configuration 
    // https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md
    var datasets = [
        {
            name: 'products',
            source: new Bloodhound( {
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace( [ 'name_code', 'id' ] ),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            identify: function( product ) {
                return product.id;
            },
            sufficient: 1,
            /*prefetch: {
                url: 'https://demo.wp-api.org/wp-json/wp/v2/categories?per_page=20',
                cacheKey: 'products',
                transform: transform_products
            },*/
            remote: {
                url: 'http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&qf=name_code%20&q=%QUERY',
                wildcard: '%QUERY',
                transform: transform_products
            },
            indexRemote: true
            } ),
            display: display_product
        }
    ];
    
    // init typeahead
    $( '#solr-typeahead' ).typeahead( {
        minLength: 2,
        highlight: true
    }, datasets );

} );

document.addEventListener('DOMContentLoaded', function() {
    console.log(solrUrl);
    //http://52.209.195.0:8984/solr/product_name_code_v2/select?defType=dismax&fl=*%2Cscore&mm=70%25&pf=name&ps=1&qf=name_code%20&q=Peusek%20Arcandol%20spray
 }, false);
