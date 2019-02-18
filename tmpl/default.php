<?php

	// CUSTOM : Entire File

    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    // no direct access
    defined('_JEXEC') or die('Restricted access');

    require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_joomdle'.'/'.'helpers'.'/'.'system.php');
    require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php');
    // require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/shop.php'); 

    //document object
    $jdoc = JFactory::getDocument();

    //add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle.css');

    $itemid = JoomdleHelperContent::getMenuItem();

    if ($linkstarget == "new")
        $target = " target='_blank'";
    else $target = "";

    if ($linkstarget == 'wrapper')
        $open_in_wrapper = 1;
    else
        $open_in_wrapper = 0;

    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');
    
?>
    <div class="joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;">
<?php
        $courseShowLimit = 0;
        if (is_array($courses))
            $courseCounter=0;
        foreach ($courses as $id => $course) //$course_info = JoomdleHelperContent::getCourseInfo($id, $username)
        {
            $id = $course['remoteid'];
			
			$teachers = $course['teachers']; //$teachers = JoomdleHelperContent::getCourseTeachers($id)
            if (count($teachers) == count($teachers, COUNT_RECURSIVE))
                // array is not multidimensional
                $teacher = $teachers;
            else
                if (is_array ($teachers))
                    $teacher = array_shift($teachers);

            if ($unicodeslugs == 1) { // Joomla SEO Settings : Unicode Aliases
                $course_slug = JFilterOutput::stringURLUnicodeSlug($course['fullname']);
                $cat_slug = JFilterOutput::stringURLUnicodeSlug($course['cat_name']);
            }
            else {
                $course_slug = JFilterOutput::stringURLSafe($course['fullname']);
                $cat_slug = JFilterOutput::stringURLSafe($course['cat_name']);
            }

            $summary_file = $course['summary_files'];
            if (is_array ($summary_file))
                $summary_file = array_shift($summary_file);
            if (count ($summary_file) == 0) // summary_file is empty
                $summary_file["url"] = JURI::root ().'modules/mod_joomdle_courses_grow/assets/image/no-image-min.png';
?>
            <div class="jf_col jf_anim_done grid_4 last-column joomdle_course_columns">
                <div class="jf_card">
                    <div class="card-main">
<?php
                        // foreach ($summary_files as $file) {
                            echo '<a style="width:100%" data-toggle="modal" data-target="'.'#modal'.$courseCounter.'" href="#" >';
?>
                                <div class="ovimg<?php echo $course['cat_id'];?> ovimg">
<?php
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="img" hspace="0" vspace="5" align="center" src="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                    /* COURSE TITLE SECTION */
                                    echo '<p class="joomdle_course_columns_titr" style="">';
                                        if ($linkto == 'moodle') {
                                            if ($default_itemid)
                                                $itemid = $default_itemid;
                                            if ($username) {
                                                echo $course['fullname'];
                                            }
                                            else
                                                if ($open_in_wrapper)
                                                    echo $course['fullname'];
                                                else
                                                    echo $course['fullname'];
                                        }
                                        else {
                                            if ($joomdle_itemid)
                                                $itemid = $joomdle_itemid;
                                            $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
                                            echo $course['fullname'];
                                        }
                                    echo '</p>';
                                echo '</div>';
                            echo '</a>';
                        // }
?>
                        <!-- TEACHER PHOTO SECTION -->
                        <div class="profcircle jf_col grid_3 last-column joomdle_course_columns_profcircle" style="color: white; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); text-align: center;display: inline-block; right: 50%; position: absolute; transform: translate(50%, -50%); -ms-transform: translate(50%, -50%); border-radius: 50%;">
<?php
                            /* require (JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php') */
                            if (!empty($teacher))
                                $teacher_user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                                // if (!count ($teacher_user_info)) //not a Joomla user
                                    // continue;
                            else
                                $teacher_user_info['pic_url'] = JURI::root ().'modules/mod_joomdle_courses_grow/assets/image/anonymous_user_avatar_100.jpg';
                                
                            // Use thumbs if available
                            if ((array_key_exists ('thumb_url', $teacher_user_info)) && ($teacher_user_info['thumb_url'] != ''))
                                $teacher_user_info['pic_url'] = $teacher_user_info['thumb_url'];
                            
                            if (!(array_key_exists ('pic_url', $teacher_user_info)) || ($teacher_user_info['pic_url'] == ''))
                                $teacher_user_info['pic_url'] = JURI::root ().'modules/mod_joomdle_courses_grow/assets/image/anonymous_user_avatar_100.jpg';
?>
                            <a class="profimg" ><img src="<?php echo $teacher_user_info['pic_url']; ?>"></a>
                        </div>

                        <!-- TEACHER NAME SECTION -->
                        <div class="profnme<?php echo $course['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid profnme" >
                            <?php if (!empty($teacher)): ?>
                                <a href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo "استاد ".$teacher['lastname']; }?></a>
                            <?php else: ?>
                                <a href="/">استاد نامشخص</a>
                            <?php endif ?>
                        </div>

                        <!-- TOPICS NUMBER SECTION -->
                        <div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php echo $course['cat_id']; ?>">
                            <b><?php echo $course['numsections']." جلسه"; ?></b>
                        </div>
                    </div>
                    
                    
                    <div style="padding:10px" id="modal<?php echo $courseCounter; ?>" class="modal fade" tabindex="-1">
                        <div class="modal-dialog waves-effect" style="cursor: crosshair;">
                            <div class="modal-content" style="border-radius: 16px;padding-bottom: 20px;">

                                <!-- POPUP EXIT BUTTON SECTION -->
                                <div class="modal-header" style="position:absolute;z-index:110;">
                                    <button class="close" type="button" data-dismiss="modal">×</button>
                                    <!-- <h4 id="myModalLabel-1-demo" class="modal-title">شرح دوره</h4> -->
                                </div>

                                <!-- POPUP SUMMARY IMAGE FILE SECTION -->
                                <div class="modalovimg<?php echo $course['cat_id']; ?> modalovimg">
                                    <img class="modalimg" style="display: block;" hspace="0" vspace="5" align="center" src="<?php echo $summary_file['url']; ?>" data-src="<?php echo $summary_file['url']; ?>" >
                                </div>

                                <!-- POPUP TEACHER NAME SECTION -->
                                <div class="modalprofnme modalprofnme<?php echo $course['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid" >
                                    <b>
                                    <?php if (!empty($teacher)): ?>
                                        <a href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo "استاد ".$teacher['lastname']; }?></a>
                                    <?php else: ?>
                                        <a href="/">استاد نامشخص</a>
                                    <?php endif ?>
                                    </b>
                                </div> 

                                <!-- POPUP TOPICS NUMBER SECTION -->
                                <div class="modalnumsec modalnumsec<?php echo $course['cat_id']; ?> jf_col_fluid ">
                                    <b><?php echo $course['numsections']." جلسه"; ?></b>
                                </div>

                                <!-- POPUP TEACHER PHOTO SECTION -->
                                <div class="modalprofimg ">
                                    <img src="<?php echo $teacher_user_info['pic_url']; ?>">
                                </div>
                                <div style="margin:20% 0px"></div> 
<?php
                                /* POPUP COURSE INFO SECTION */
                                echo '<div class="modal-body modal-body'.$course['cat_id'].'" style="padding:0px 32px; text-align-all: center">';
                                    if ($course['summary'])
                                        echo '<div>'.JoomdleHelperSystem::fix_text_format($course['summary']).'</div>';
                                    if ($linkto == 'moodle') {
                                        if ($default_itemid)
                                            $itemid = $default_itemid;

                                        if ($username) {
                                            echo "<a $target href=\"".$moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid \">اطلاعات بیشتر ...</a><br>";
                                        }
                                        else
                                            if ($open_in_wrapper)
                                                echo "<a $target href=\"".$moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid \">اطلاعات بیشتر ...</a><br>";
                                            else
                                                echo "<a $target href=\"".$moodle_url."/course/view.php?id=$id \">اطلاعات بیشتر...</a><br>";
                                    }
                                    else {
                                        if ($joomdle_itemid)
                                            $itemid = $joomdle_itemid;

                                        $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
                                    }
                                echo '</div>';

                                /* POPUP MORE LINK AND ENROLL BUTTON SECTION */
                                echo '<div class="inlineForm center" style="padding:10px" >';
                                    echo JoomdleHelperSystem::actionbutton ( $course );
                                    echo "<a style=\"direction:rtl\" href=\"".$url."\">اطلاعات بیشتر...</a><br>";
                                echo '</div>';
                            echo '</div>';
                        echo '</div>'; 
                    echo '</div>'; 
                echo '</div>'; 
            echo '</div>'; 
            $courseCounter++;
            $courseShowLimit++;
            if ($courseShowLimit >= $limit) // Show only this number of latest courses
                break; 
        }
?>
    </div>