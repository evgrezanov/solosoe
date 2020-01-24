<?php

namespace FRMBS\SolrRequest;

defined('ABSPATH') || exit;

/**
 * Class SolrRequest
 */
class SolrRequest {
    
    /**
     * SolrRequest init.
     */
    public static function init() {
        add_filter('pre_get_posts', [__CLASS__, 'solr_search_query']);
        //add_filter('template_include', [__CLASS__, 'frm_base_custom_search_template']);
        add_action('solr_display_response', [__CLASS__, 'solr_display_response']);
    }

    public static function frm_base_custom_search_template($template){
        ?>  
        <div class="container p-3">
	        <h2>Twitter typeahead.js and the WordPress REST API</h2>

	    <form>
		    <div class="form-group">
			    <label for="pronamic-typeahead-example">Post</label>

			    <input id="pronamic-typeahead-example" class="form-control" />
		    </div>
	    </form>
    </div>
    <?php 
    }
}
SolrRequest::init();
