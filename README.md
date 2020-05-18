# moved to [owner repository](https://github.com/gerardodiazcorujo/SoloSoe)

## WordPress plug-in for auto-compleate search (twitter typehead) in Solr database
### Requirements
- Integrate typehead.js for auto-compleate search
- Get search result from Solr response by 2indexs
- Display search results in autocompleate
- After click on product at autocomplete make 2 requests
- http://34.243.79.103:8000/services/product/144615/
- http://34.243.79.103:8000/services/optimal-price/144615/
- we call this id >6 (first charecter)
- https://cima.aemps.es/cima/rest/medicamento?cn=912485
- https://cima.aemps.es/cima/rest/psuministro/912485
- Display different template for different response
