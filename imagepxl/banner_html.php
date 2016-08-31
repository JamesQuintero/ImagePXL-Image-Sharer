<table style="position:absolute;z-index:2;bottom:0px;width:100%;">
    <tbody>
        <tr>
            <td>
                <input type="button" class="button red_button" value="Remove Banner" onClick="remove_banner();" style="float:left;"/>
            </td>
            <td>
                <form method="post" action="../change_banner.php" enctype="multipart/form-data" target="banner_upload_iframe" >
                    <table style="float:right;">
                        <tbody>
                            <tr>
                                <td>
                                    <input type="file" id="image" name="image" class="text_color"/>
                                </td>
                                <td>
                                    <input type="submit" class="button red_button" value="Upload" id="submit" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        </tr>
    </tbody>
</table>