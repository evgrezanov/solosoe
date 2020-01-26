var autoComplete = function(){
  var dataset = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.ngram,
      queryTokenizer: Bloodhound.tokenizers.ngram,
      remote: {
          url: '/api/search/%QUERY',
          wildcard: '%QUERY' 
      }
  });
  dataset.initialize();
  $('.typeahead').typeahead({
      hint: true,
      highlight: true,
      minLength: 2 
  }, {
      name: 'products',
      displayKey: 'name',
      source: dataset.ttAdapter(), 
      limit: 100,
      templates: {
          notFound: function(data){
              return '{the html you want when no results found}';
          },
          pending: function(data){
              return '{the html you want for when the user is typing and search is being performed}';
          },
          suggestion: function(data) {
              return '{the html you want for each of the suggestions}';
          }
      }
  });
};
#the data above responds to the fields sent in the api response, for example for the api we created here, we can get the name of the member entity by calling data.name, and source by calling data.source