# Git to FTP
A tool for uploading files committed by Git to FTP Server wheres git is not available for pulling commits.

# How to Use
<ol>
<li>Just download whole code OR clone the repository to your working project </li>
<li>copy config.sample.php to config.php </li>
<li>Set following variables 
  <ul>
  <li>BASE_URL : url of Git_to_FTP</li>
  <li>DEVELOPER : name of developer</li>
  <li>GIT_PATH : This is absolute path of your project where .git folder located</li>
  <li>FTP_SERVER, FTP_USER, FTP_PASSWORD are FTP Details</li>
  <li>FTP_PROJECT_PATH is path of project at Server e.g. /public_html/my_project/</li>
  <li>LAST_SYNC_DATETME(optional) //let the tool know if you alerdy upload files manually</li>
  </ul>
</li>
</ol>
