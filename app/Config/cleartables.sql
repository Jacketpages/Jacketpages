update users
set gtUsername = Concat('Person: ', id),
email = Concat('Email: ', id),
name = Concat('Person: ', id),
first_name = 'Person',
last_name = id,
local_address_line_1 = 'Here!',
local_address_line_2 = '',
local_city = 'Atlanta',
local_state = 'Georgia',
home_address_line_1 = 'Here!',
home_address_line_2 = '',
home_city = 'Atlanta',
home_state = 'Georgia'
LIMIT 100000;