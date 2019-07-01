<?php
/*
UPDATE studentinfo 
SET studentinfo.Name = CONCAT(
    UPPER(SUBSTRING(Name,1,1)),
    LOWER(SUBSTRING(Name,2,Locate(' ', Name)-1)),
    UPPER(SUBSTRING(Name,Locate(' ', Name)+1,1)),
    LOWER(SUBSTRING(Name,Locate(' ', Name)+2)))
WHERE classid = ?;
*/
?>