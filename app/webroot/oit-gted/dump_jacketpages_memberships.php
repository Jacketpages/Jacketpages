<?
//error handler function
function customError($errno, $errstr)
  {
  echo "Error: [$errno] $errstr\n";
  echo "Ending Script\n";
  die();
  }

//set error handler
set_error_handler("customError");

mysql_connect("web-db3.gatech.edu", "user_jp", "Justice1");

function printMemberships($filter, $resultingRole)
{
  $queryTemplate = 
   "SELECT gt_user_name,  
          concat('STUDENT_ORG_VALUE_PREFIX', trim(replace(o.name, '/', '-')), '/', ROLE_RESULT) as value
    FROM jacketpages.memberships m
    JOIN jacketpages.users u on (m.user_id=u.id)
    JOIN jacketpages.organizations o ON (m.org_id=o.id)
    WHERE
    (m.start_date IS NULL OR m.start_date=0 OR m.start_date <= current_date)
    AND (m.end_date is null OR m.end_date=0 OR m.end_date > current_date)
    AND o.status NOT IN ('Frozen', 'Inactive')
    AND  (FILTER)
  ORDER BY 1,2";

  $query=$queryTemplate;
  $query=str_replace('ROLE_RESULT', $resultingRole, $query);
  $query=str_replace('FILTER',$filter, $query);

  // echo "Running query $query\n";
  $result=mysql_query($query);

  $count=mysql_numrows($result);

  $i=0;
  while ($i < $count)
  {
    $username=mysql_result($result, $i, 0);
    $value=mysql_result($result, $i, 1);
    echo "$username,\"$value\"\n";
    $i++;
  }
}

echo "Content-Type: text/plain\n";
echo "\n";


printMemberships("(room_reserver is not null and room_reserver <> 'No' and room_reserver <> '') OR m.role IN ('President', 'Room Reserver', 'Advisor')", "'Room Reserver'");
printMemberships("m.role IN ('President', 'Treasurer', 'Officer')", "'Officer'");
#print all roles but members
#printMemberships("m.role <> 'member'", "m.role");

# This prints all roles (including members)
printMemberships("1=1", "m.role");

mysql_close();


?>
