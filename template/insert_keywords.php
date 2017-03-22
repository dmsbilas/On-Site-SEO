<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

$args = array(
'post_type' => 'page',
'post_status' => 'publish'
);
$pages = get_pages($args);
?>

  <table>
  <hr />
    <tr>
      <td>
        <select id="page_selector" name="pagename">
          <?php foreach($pages as $key => $value){?>
            <option value="<?php echo $value->post_name ?>">
              <?php echo $value->post_title ?>
            </option>
            <?php } ?>
        </select>
      </td>
      <td>
        <textarea id="keywords" rows="5" cols="65" name="keywords" value="">
          <?php echo trim(get_post_meta($value->ID, $value->post_name, true)); ?>
        </textarea>
      </td>
      <td>
        <span id="loader" hidden><img style="width:100px; height:100px;opacity:.5;border-radius:60px" src="<?php echo plugins_url( '../images/65.gif', __FILE__ );?>"> </span>
      </td>
      <td>
        <button class="btn_save_keywords"> Save Keywords </button>
      </td>
    </tr>
  </table>

  <hr/>

  <table style="width:100%">
    <tr style="text-align:left;">
      <th style="min-width: 200px"><strong>Page Name</strong></th>
      <th><strong>Keywords</strong></th>
    </tr>
    <?php foreach ($pages as $key => $value) { ?>
      <tr id="<?php echo $value->post_name; ?>">
        <td>
          <?php echo $value->post_title; ?>
        </td>
        <td>
          <?php echo get_post_meta($value->ID,$value->post_name, true); ?>
        </td>
      </tr>
      <?php } ?>


  </table>

  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('#page_selector').on('change', function() {
        $('#loader').show();
        $('#keywords').hide();
        var page_slug = $(this).val();
        var page_title = $('#page_selector option:selected').text();
        var ajax_url = "<?php echo admin_url('admin-ajax.php') ?>";

        jQuery.ajax({
          url: ajax_url,
          type: 'post',
          data: {
            action: 'get_keywords_by_page_slug',
            page_slug: page_slug,
            page_title: page_title.trim()
          },
          success: function(response) {
            $('#keywords').val($.parseJSON(response));
            // console.log(response);
            $('#loader').hide();
            $('#keywords').show();
          }
        });

      });

      $('.btn_save_keywords').on('click', function() {
        var page_slug = $('#page_selector').val();
        var page_title = $('#page_selector option:selected').text().trim();
        var keywords = $('#keywords').val().trim();
        var ajax_url = "<?php echo admin_url('admin-ajax.php') ?>";
        $('#loader').show();
        $('#keywords').hide();
        jQuery.ajax({
          url: ajax_url,
          type: 'post',
          data: {
            action: 'add_keywords_into_page',
            page_slug: page_slug,
            page_title: page_title,
            keywords: keywords
          },
          success: function(response) {
            $('tr#' + page_slug).html(
              "<td>" + page_title.trim() + "</td><td>" + keywords.trim() + "</td>"
            );
            $('#loader').hide();
            $('#keywords').show();
          }
        });
      });
    });
  </script>