<style>
    .panel-body
    {
        padding: 0;
    }
    .table
    {
        margin-bottom: 0;
    }
    
    .error-msg
    {
        display: none;
    }
</style>

<div id="progress" >
    <div class="text-right">
        <span class="percentage">0%</span> complete
    </div>
    <div class="progress">    
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
            <span class="sr-only">0% Complete</span>
        </div>
    </div>
</div>

<div class="alert alert-danger error-msg" role="alert">
</div>

<div class="row">
    <div class="col-md-6">
        <div class="page-header">
            <h3>
                Git (<?= $branch_name; ?>)
            </h3>
        </div>
        
        <div class="panel panel-info">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="width : 70%; padding-top: 7px;">
                   Commits
                </h4>
                <div class="pull-right" style="width : 20%; text-align: right;">
                    <button class="btn btn-primary" id="ftp-link-upload">
                        <i class="glyphicon glyphicon-cloud-upload"></i>
                    </button>
                </div>
            </div>
            <div class="panel-body"  style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:5%">
                                #
                            </th>
                            <th>Commit Message</th>
                            <th>Author</th>
                            <th style="width:10%">Datetime</th>     
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach($commits as $k => $commit): $i++; ?>
                        <tr class="commit_<?= $commit['commit'] ?>">
                            <td>
                                <?= $i ?>
                            </td>
                            <td>
                                <?= $commit['msg'] ?>
                                <p><a href="javascript:void(0);" class="css-toggler" data-toggler-target="tr#commit<?= $i ?>" data-toggler-class="hidden">Show Files</a></p>
                            </td>
                            <td><?= $commit['author'] ?></td>
                            <td><?= DateUtility::getDate($commit['datetime'], DateUtility::DATETIME_OUT_FORMAT); ?></td>
                        </tr>
                        <tr id="commit<?= $i ?>" class="hidden commit_<?= $commit['commit'] ?>">
                            <td></td>
                            <td colspan="3">
                                <ol>
                                <?php foreach($commit['files'] as $file):?>
                                    <li>
                                        <?php $cls = $file['is_exist'] ? "" : "text-danger"; ?>
                                        <span class="<?= $cls ?>">
                                            <?= $file['file'] ?>
                                            <?php 
                                                if (!$file['is_exist'])
                                                {
                                                    echo "[-]";
                                                }
                                            ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                                </ol>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6" id="ftp-block">
        
        <div class="page-header">
            <h3>FTP (<?= FTP_SERVER ?>)</h3>
        </div>
        
        <div class="panel panel-info">
            <div class="panel-heading clearfix">         
                <div class="row">
                    <div class="col-md-3" style="padding-right : 0;">
                        <button class="btn btn-primary" id="ftp-link-back">
                            <i class="glyphicon glyphicon-arrow-left"></i>
                        </button>
                        <button class="btn btn-primary" id="ftp-link-home">
                            <i class="glyphicon glyphicon-home"></i>
                        </button>                    
                    </div>
                    <div class="col-md-7" style="padding-left : 0;">
                        <input type="text" id="ftp-address-bar" class="form-control" />
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <button class="btn btn-primary" id="ftp-link-refresh">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="ftp-listing" class="panel-body tf-loader-wrapper" style="height : 400px; overflow-y: auto;">
                <div class="tf-loader-container" data-loader-radius="8"></div>
                <table class="table table-bordered table-striped">
                    <tbody id="ftp-window" data-ftp-home-path="<?= FTP_PROJECT_PATH ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var commits = JSON.parse('<?= json_encode($commits); ?>');
    var errorObj;
    var process_contiue = false;
    $(document).ready(function() 
    {
        $("#progress").hide();
        errorObj = $(".error-msg");
        errorObj.hide();
        
        function ftp_address_filter()
        {
            var address = $("#ftp-address-bar").val().trim(" ");
            address = address.replace(/\\/g, "/");
            var paths = address.split("/");
            var c = paths.length;
            for(var i = 0; i < c; i++)
            {
                if (typeof paths[i] != "undefined")
                {
                    paths[i] = paths[i].trim(" ").trim("/");
                    if (paths[i] == "")
                    {
                        paths.splice(i, 1);
                    }
                }
            }
            
            address = paths.join("/");
            if (address != "")
            {
                address += "/";
            }
            
            $("#ftp-address-bar").val(address);
            return address;
        }
        
        $("#ftp-link-refresh").click(function()
        {
            $("#ftp-listing").beginLoader();
            
            var address = ftp_address_filter();
            if (address == "")
            {
                $("#ftp-link-back").addClass("disabled").attr("disabled" , true);
            }
            else
            {
                $("#ftp-link-back").removeClass("disabled").removeAttr("disabled");
            }
            
            var path = $("#ftp-window").attr("data-ftp-home-path") + address;
            
            errorObj.hide();
            $.post("<?= url("ftp_dir_list", array(), "ajax") ?>", { ftp_path : path }, function (data, status)
            {
                if (status != "success")
                {
                    errorObj.html(data).show();
                    return;
                }
                
                try
                {
                    data  = JSON.parse(data);
                }
                catch(e)
                {
                    errorObj.html(data).show();
                    return;
                }
                
                var tbody = "";
                var k = Object.keys(data);
                if (k.length > 0)
                {
                    for(var i in data)
                    {
                        if (data[i]['obj'] == "." || data[i]['obj'] == "..")
                        {
                            continue;
                        }
                        
                        tbody += "<tr><td>";
                        
                        if (data[i]['is_dir'] == "1")
                        {
                            tbody += "<a href='javascript:void(0);' class='ftp-link-dir'>" + data[i]['obj'] + "</a>";
                        }
                        else
                        {
                            tbody += data[i]['obj'];
                        }
                         
                        tbody += "</td></tr>";
                    }
                }
                else
                {
                    tbody += "<tr><td>No Files</td></tr>";
                }
                
                $("#ftp-window").html(tbody);
                
                $("#ftp-listing").stopLoader();
                
            }).fail(function (data)
            {
                errorObj.html(data.responseText).show();
            });
        });
        
        $(document).on("click", ".ftp-link-dir", function()
        {
            var dir = $(this).html();
            var address = $("#ftp-address-bar").val();
            $("#ftp-address-bar").val(address + dir + "/");
            $("#ftp-link-refresh").trigger("click");
        });
        
        $("#ftp-link-home").click(function()
        {
            $("#ftp-address-bar").val("");
            $("#ftp-link-refresh").trigger("click");
        });
        
        $("#ftp-link-back").click(function()
        {
            if ($(this).is(":disabled"))
            {
                return;
            }
            
            var current = ftp_address_filter();
            var paths = current.split("/");
            
            //check for space
            var c = paths.length;
            for(var i = 0; i < c; i++)
            {
                if (typeof  paths[i] != "undefined")
                {
                    paths[i] = paths[i].trim(" ");
                    if (paths[i] == "")
                    {
                        paths.splice(i, 1);
                    }
                }
            }
            paths.pop();
            
            if (paths.length > 0)
            {
                current = paths.join("/");
            }
            else
            {
                current = "";
            }
            
            $("#ftp-address-bar").val(current);
            $("#ftp-link-refresh").trigger("click");
        });
        
        $("#ftp-link-upload").click(function ()
        {
            errorObj.hide();
            
            var post_commits = [];
            for(var i in commits)
            {
                post_commits.push({
                    data : commits[i],
                    will_upload : 1
                });
            }
            
            start_process(post_commits);
        });
    });
    
    function start_process(post_commits)
    {
        $("#progress").show();
        process_contiue = true;
        
        var filename = "files/temp/commit_files_" + (new Date()).getTime() + ".csv";
        $.post('<?= url("ftp_upload", array(), "ajax") ?>', { commits : post_commits, filename : filename}, function(data, status)
        {
            if (status != "success")
            {
                errorObj.html(data).show();
                return;
            }
            
            if (data != "1")
            {
                process_contiue = false;
                errorObj.html(data).show();
                return;
            }
        })
        .fail(function (data)
        {
            process_contiue = false;
            errorObj.html(data.responseText).show();
        });
        
        track_process(filename);
    }
    
    function track_process(filename)
    {
        if (!process_contiue)
        {
            return;
        }
        
        $.post('<?= url("ftp_track_upload", array(), "ajax") ?>', { filename : filename}, function(data, status)
        {
            if (status != "success")
            {
                errorObj.html(data).show();
                return;
            }
            else
            {
                try
                {
                    data = JSON.parse(data);
                }
                catch(e)
                {
                    errorObj.html(data).show();
                    return;
                }
                
                var per = Math.round(data.done_count * 100 / data.total_count);
                $("#progress .progress-bar").css("width", per + "%");
                $("#progress .percentage").html(per + "%");
                
                for (var i in data.commit_list)
                {
                    var commit = data.commit_list[i];
                    console.log(commit);
                    $(".commit_" + commit).fadeOut("slow");
                }
                
                if (per < 100)
                {
                    setTimeout(function() {
                        track_process(filename);
                    }, 1000);                    
                }
            }
        }).fail(function (data)
        {
            process_contiue = false;
            errorObj.html(data.responseText).show();
        });
    }
</script>
