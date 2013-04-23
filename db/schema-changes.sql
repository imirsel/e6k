alter table mirex_E6K_Tasks add column task_Consent_Form varchar(255);
alter table mirex_E6K_Tasks add column task_Evaluation_Form varchar(255);
alter table mirex_E6K_Consent add column consent_Form varchar(255);

create table mirex_E6K_Subtask (
	input_ID int primary key NOT NULL AUTO_INCREMENT,
	input_Task int,
	input_Sub_Task int,
	input_Name varchar(50),
	input_Value varchar(255)
);

create table mirex_E6K_Subtask_Assignments (
	assign_Task int not null,
	assign_Sub_Task int not null,
	assign_Timestamp timestamp, 
	assign_Grader varchar(150)
);

create table mirex_E6K_Subtask_Results (
	result_Task int not null,
	result_Sub_Task int not null,
	result_Grader varchar(150),
	result_Timestamp timestamp,
	result_Name varchar(50), 
	result_Value varchar(150)
);
