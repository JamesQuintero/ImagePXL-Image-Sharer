var handleUpload=function(event){
    event.preventDefault();
    event.stopPropagation();
    
    var fileInput=document.getElementById('image');
    
    var description=$('#image_description_upload').val();
    
    //clears image file input and description
    $('#image_description_upload').val('');
    
    
    var album=$('#album_select').val();
    
    var data=new FormData();
    if($('#nsfw').is(':checked'))
        var nsfw="true";
    else
        var nsfw="false";
    
    data.append('javascript', 'true');
    data.append('description', description);
    data.append('album', album);
    data.append('nsfw', nsfw);
    
    var large_file_count=0;
    var good_file=false;
    
    for(var x = 0; x < fileInput.files.length; x++)
    {
        //less than 1GB
        if(fileInput.files[x].size<10240000)
        {
            data.append('image[]', fileInput.files[x]);
            good_file=true;
        }
        else
            large_file_count++;
    }    
    
    if(large_file_count!=0)
        display_error(large_file_count+" images were too big. They have been excluded from upload", 'bad_errors');
    
    if(good_file)
    {
        var request=new XMLHttpRequest();

        //gets progress
        request.upload.addEventListener('progress', function(event){
            if(event.lengthComputable){
                var percent= Math.round((event.loaded / event.total)*100);

                $('#progress_bar').progressbar("option", {value: percent});
                $('#percent_loaded').html(percent+"%");
            }
        });

        //after it's loaded
        request.upload.addEventListener('load', function(event)
        {
//            setTimeout(function(){
//                $('#upload_image_gif').hide();
//                    load_recent_pictures();
//                    load_albums_list();
//                    $('#progress_bar').hide();
//                    $('#percent_loaded').hide();
//                   display_error("Images uploaded successfully", 'good_errors');
//            }, 3000);
        });

        //errors
        request.upload.addEventListener('error', function(event){
            console.log("Upload failed");
        });

        //after uploads complete
        request.addEventListener('readystatechange', function(event){
            if(this.readyState==4)
            {
               if(this.status==200)
               {
                   if($('#logged_in_icon').length==0)
                   {
                       console.log(request.responseText);
                       window.location.replace("http://imagepxl.com/"+request.responseText);
                   }
                   
                   setTimeout(function(){
                        $('#upload_image_gif').hide();
                        load_recent_pictures();
                        load_albums_list();
                        $('#progress_bar').hide();
                        $('#percent_loaded').hide();
                   }, 1000);

               }
               else
                   console.log("Server replied with HTTP status "+this.status);
            }
        });

        request.open('POST', 'upload_image.php');
        request.setRequestHeader('Cache-Control', 'no-cache');

        //displays progress stuff
        $('#upload_image_gif').show();
        display_progress_bar();

        //sends request data
        request.send(data);
    }
}

window.addEventListener('load', function(event)
{
   var submit=document.getElementById('submit');
   submit.addEventListener('click', handleUpload);
});