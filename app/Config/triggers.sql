DELIMITER //
CREATE TRIGGER bills_insert BEFORE INSERT ON BILLS
FOR EACH ROW
SET NEW.SUBMIT_DATE = CURDATE();
//

DELIMITER //
CREATE TRIGGER memberships_insert BEFORE INSERT ON MEMBERSHIPS
FOR EACH ROW
SET NEW.START_DATE = CURDATE();
//

/*DELIMITER //
CREATE TRIGGER BUDGETS_INSERT BEFORE INSERT ON BUDGETS
FOR EACH ROW
SET NEW.UPDATED = NOW();
//*/

DELIMITER //
CREATE TRIGGER lineitems_before_delete BEFORE DELETE ON line_items 
FOR EACH ROW 
INSERT INTO line_item_revisions
( line_item_id,
  line_number,
  bill_id,
  parent_id,
  state,
  name,
  cost_per_unit,
  quantity,
  total_cost,
  amount,
  account,
  type,
  comments,
  revision,
  struck,
  deleted
)
values
( old.id,
  old.line_number,
  old.bill_id,
  old.parent_id,
  old.state,
  old.name,
  old.cost_per_unit,
  old.quantity,
  old.total_cost,
  old.amount,
  old.account,
  old.type,
  old.comments,
  (select max(revision) + 1 from line_item_revisions l where l.line_item_id = old.id),
  old.struck,
  1 
);
//

DELIMITER //
CREATE TRIGGER lineitems_before_insert BEFORE INSERT ON line_items 
FOR EACH ROW 
INSERT INTO line_item_revisions
( line_item_id,
  line_number,
  bill_id,
  parent_id,
  state,
  name,
  cost_per_unit,
  quantity,
  total_cost,
  amount,
  account,
  type,
  comments,
  revision,
  struck,
  deleted
)
values
( NEW.id,
  NEW.line_number,
  NEW.bill_id,
  NEW.parent_id,
  NEW.state,
  NEW.name,
  NEW.cost_per_unit,
  NEW.quantity,
  NEW.total_cost,
  NEW.amount,
  NEW.account,
  NEW.type,
  NEW.comments,
  1,
  new.struck,
  0
);
//