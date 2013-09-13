CREATE TABLE IF NOT EXISTS budgets
(
	id INT(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (id),
	org_id INT(11),
	average_attendance int(10),
	summer_meetings int(1),
	faculty_member_count int(10),
	non_gt_member_count int(10),
	fiscal_year int(4),
	state varchar(10),
	last_mod_date timestamp,
	last_mod_by int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS budget_line_items
(
	id int(11) not null auto_increment,
	primary key (id),
	budget_id int(11),
	line_number int(11),
	category int(2),
	name varchar(200),
	state varchar(10),
	amount decimal(10,2),
	comments text,
	viewable int(1),
	last_mod_date timestamp,
	last_mod_by int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS fundraising
(
	id int(11) not null auto_increment,
	primary key(id),
	activity varchar(200),
	date date,
	revenue decimal(10,2),
	budget_id int(11),
	type varchar(10)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS expenses
(
	id int(11) not null auto_increment,
	primary key (id),
	budget_id int(11),
	item varchar(200),
	expense decimal(10,2)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS line_item_categories
(
	id int(11) not null auto_increment,
	primary key (id),
	name varchar(20),
	description varchar(100)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS budget_submit_state
(
	budget_id int(11) not null,
	primary key (budget_id),
	state_1 int(1),
	state_2 int(1),
	state_3 int(1),
	state_4 int(1),
	state_5 int(1)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS dues
(
	id int(11) not null auto_increment,
	primary key (id),
	budget_id int(11),
	member_category varchar(20),
	amount decimal(10,2),
	members int(11)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS assets
(
	id int(11) not null auto_increment,
	primary key (id),
	budget_id int(11),
	item varchar(200),
	amount decimal(10,2)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS liabilities
(
	id int(11) not null auto_increment,
	primary key (id),
	budget_id int(11),
	item varchar(200),
	amount decimal(10,2)
)ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;