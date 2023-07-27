<?php
get_header();
?>
<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <section class="error-404 clearfix">
                <div class="left-col">
                    <p><?php _e('404', kopa_get_domain());?></p>
                </div><!--left-col-->
                <div class="right-col">
                    <h1><?php _e('Page not found...', kopa_get_domain());?></h1>
                    <p><?php _e("We're sorry, but we can't find the page you were looking for. It's probably some thing we've done wrong but now we know about it we'll try to fix it. In the meantime, try one of this options:", kopa_get_domain());?></p>
                    <ul class="arrow-list">
                        <li><a href="javascript: history.go(-1);"><?php _e('Go back to previous page', kopa_get_domain());?></a></li>
                        <li><a href="<?php echo home_url(); ?>"><?php _e('Go to homepage', kopa_get_domain());?></a></li>
                    </ul>
                </div><!--right-col-->
            </section><!--error-404-->
        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();