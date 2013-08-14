create table if not exists searches
(
	id int(11) unsigned not null auto_increment,
	primary key(id),
	term varchar(200) not null,
	occurrences int(11) not null,
	last_searched timestamp DEFAULT CURRENT_TIMESTAMP on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1