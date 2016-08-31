<div class="content" id="profile_customization_div">
    <table style="padding:15px;">
        <tbody>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="text_color">Current theme:</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="theme <?php $theme=get_current_theme(); echo $theme;  ?>_theme" ></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="vertical-align:bottom;">
                                    <table style="padding-left:50px;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="theme normal_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('normal');" ></div>
                                                </td>
                                                <td>
                                                    <div class="theme dark_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('dark');" ></div>
                                                </td>
                                                <td>
                                                    <div class="theme red_black_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('red_black');" ></div>
                                                </td>
                                                <td>
                                                    <div class="theme yellow_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('yellow');" ></div>
                                                </td>
                                                <td>
                                                    <div class="theme green_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('green');" ></div>
                                                </td>
                                                <td>
                                                    <div class="theme blue_theme" onmouseover="theme_over(this);" onmouseout="theme_out(this);" onClick="change_theme('blue');" ></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>