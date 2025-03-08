-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema sysapae
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sysapae
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sysapae` DEFAULT CHARACTER SET utf8mb4 ;
USE `sysapae` ;

-- -----------------------------------------------------
-- Table `sysapae`.`system_document`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_document` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `title` VARCHAR(256) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `submission_date` DATE NULL DEFAULT NULL,
  `archive_date` DATE NULL DEFAULT NULL,
  `filename` VARCHAR(512) NULL DEFAULT NULL,
  `in_trash` CHAR(1) NULL DEFAULT NULL,
  `system_folder_id` INT NULL DEFAULT NULL,
  `content` TEXT NULL DEFAULT NULL,
  `content_type` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_document_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_document_folder_idx` (`system_folder_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_document_bookmark`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_document_bookmark` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_document_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_document_bookmark_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_document_bookmark_document_idx` (`system_document_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_document_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_document_group` (
  `id` INT NOT NULL,
  `document_id` INT NULL DEFAULT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_document_group_document_idx` (`document_id` ASC) VISIBLE,
  INDEX `sys_document_group_group_idx` (`system_group_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_document_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_document_user` (
  `id` INT NOT NULL,
  `document_id` INT NULL DEFAULT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_document_user_document_idx` (`document_id` ASC) VISIBLE,
  INDEX `sys_document_user_user_idx` (`system_user_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_folder`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_folder` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  `name` VARCHAR(256) NOT NULL,
  `in_trash` CHAR(1) NULL DEFAULT NULL,
  `system_folder_parent_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_folder_user_id_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_folder_name_idx` (`name` ASC) VISIBLE,
  INDEX `sys_folder_parend_id_idx` (`system_folder_parent_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_folder_bookmark`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_folder_bookmark` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_folder_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_folder_bookmark_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_folder_bookmark_folder_idx` (`system_folder_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_folder_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_folder_group` (
  `id` INT NOT NULL,
  `system_folder_id` INT NULL DEFAULT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_folder_group_folder_idx` (`system_folder_id` ASC) VISIBLE,
  INDEX `sys_folder_group_group_idx` (`system_group_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_folder_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_folder_user` (
  `id` INT NOT NULL,
  `system_folder_id` INT NULL DEFAULT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_folder_user_folder_idx` (`system_folder_id` ASC) VISIBLE,
  INDEX `sys_folder_user_user_idx` (`system_user_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_group` (
  `id` INT NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_group_name_idx` (`name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_program` (
  `id` INT NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  `controller` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_program_name_idx` (`name` ASC) VISIBLE,
  INDEX `sys_program_controller_idx` (`controller` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_group_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_group_program` (
  `id` INT NOT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  `system_program_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_group_program_program_idx` (`system_program_id` ASC) VISIBLE,
  INDEX `sys_group_program_group_idx` (`system_group_id` ASC) VISIBLE,
  CONSTRAINT `system_group_program_ibfk_1`
    FOREIGN KEY (`system_group_id`)
    REFERENCES `sysapae`.`system_group` (`id`),
  CONSTRAINT `system_group_program_ibfk_2`
    FOREIGN KEY (`system_program_id`)
    REFERENCES `sysapae`.`system_program` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_message` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_user_to_id` INT NULL DEFAULT NULL,
  `subject` VARCHAR(256) NULL DEFAULT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `dt_message` VARCHAR(20) NULL DEFAULT NULL,
  `checked` CHAR(1) NULL DEFAULT NULL,
  `removed` CHAR(1) NULL DEFAULT NULL,
  `viewed` CHAR(1) NULL DEFAULT NULL,
  `attachments` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_message_user_id_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_message_user_to_idx` (`system_user_to_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_message_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_message_tag` (
  `id` INT NOT NULL,
  `system_message_id` INT NOT NULL,
  `tag` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_message_tag_msg_idx` (`system_message_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_notification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_notification` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_user_to_id` INT NULL DEFAULT NULL,
  `subject` VARCHAR(256) NULL DEFAULT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `dt_message` VARCHAR(20) NULL DEFAULT NULL,
  `action_url` TEXT NULL DEFAULT NULL,
  `action_label` VARCHAR(256) NULL DEFAULT NULL,
  `icon` VARCHAR(100) NULL DEFAULT NULL,
  `checked` CHAR(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_notification_user_id_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_notification_user_to_idx` (`system_user_to_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_post` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `title` VARCHAR(256) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  `updated_at` VARCHAR(20) NULL DEFAULT NULL,
  `updated_by` INT NULL DEFAULT NULL,
  `active` CHAR(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  INDEX `sys_post_user_idx` (`system_user_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_post_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_post_comment` (
  `id` INT NOT NULL,
  `comment` TEXT NOT NULL,
  `system_user_id` INT NOT NULL,
  `system_post_id` INT NOT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_post_comment_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_post_comment_post_idx` (`system_post_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_post_like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_post_like` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_post_id` INT NOT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_post_like_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_post_like_post_idx` (`system_post_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_post_share_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_post_share_group` (
  `id` INT NOT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  `system_post_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_post_share_group_group_idx` (`system_group_id` ASC) VISIBLE,
  INDEX `sys_post_share_group_post_idx` (`system_post_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_post_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_post_tag` (
  `id` INT NOT NULL,
  `system_post_id` INT NOT NULL,
  `tag` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_post_tag_post_idx` (`system_post_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_preference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_preference` (
  `id` VARCHAR(256) NULL DEFAULT NULL,
  `value` TEXT NULL DEFAULT NULL,
  INDEX `sys_preference_id_idx` (`id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_role` (
  `id` INT NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  `custom_code` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_role_name_idx` (`name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_program_method_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_program_method_role` (
  `id` INT NOT NULL,
  `system_program_id` INT NULL DEFAULT NULL,
  `system_role_id` INT NULL DEFAULT NULL,
  `method_name` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_program_method_role_program_idx` (`system_program_id` ASC) VISIBLE,
  INDEX `sys_program_method_role_role_idx` (`system_role_id` ASC) VISIBLE,
  CONSTRAINT `system_program_method_role_ibfk_1`
    FOREIGN KEY (`system_program_id`)
    REFERENCES `sysapae`.`system_program` (`id`),
  CONSTRAINT `system_program_method_role_ibfk_2`
    FOREIGN KEY (`system_role_id`)
    REFERENCES `sysapae`.`system_role` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_schedule` (
  `id` INT NOT NULL,
  `schedule_type` CHAR(1) NULL DEFAULT NULL,
  `title` VARCHAR(256) NULL DEFAULT NULL,
  `class_name` VARCHAR(256) NULL DEFAULT NULL,
  `method` VARCHAR(256) NULL DEFAULT NULL,
  `monthday` CHAR(2) NULL DEFAULT NULL,
  `weekday` CHAR(1) NULL DEFAULT NULL,
  `hour` CHAR(2) NULL DEFAULT NULL,
  `minute` CHAR(2) NULL DEFAULT NULL,
  `active` CHAR(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_unit` (
  `id` INT NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  `connection_name` VARCHAR(256) NULL DEFAULT NULL,
  `custom_code` VARCHAR(256) NULL DEFAULT NULL,
  `alias` VARCHAR(256) NULL,
  `cpf_cnpj` VARCHAR(45) NULL,
  `phone` VARCHAR(45) NULL,
  `phone2` VARCHAR(45) NULL,
  `email` VARCHAR(256) NULL,
  `cep` VARCHAR(45) NULL,
  `address` VARCHAR(256) NULL,
  `number` VARCHAR(45) NULL,
  `complement` VARCHAR(256) NULL,
  `district` VARCHAR(45) NULL,
  `city` VARCHAR(100) NULL,
  `uf` VARCHAR(2) NULL,
  `reference` VARCHAR(256) NULL,
  `active` CHAR(1) NULL DEFAULT 'Y',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `sys_unit_name_idx` (`name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_users` (
  `id` INT NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  `login` VARCHAR(256) NULL DEFAULT NULL,
  `password` VARCHAR(256) NULL DEFAULT NULL,
  `email` VARCHAR(256) NULL DEFAULT NULL,
  `accepted_term_policy` CHAR(1) NULL DEFAULT NULL,
  `phone` VARCHAR(256) NULL DEFAULT NULL,
  `address` VARCHAR(256) NULL DEFAULT NULL,
  `function_name` VARCHAR(256) NULL DEFAULT NULL,
  `about` TEXT NULL DEFAULT NULL,
  `accepted_term_policy_at` VARCHAR(20) NULL DEFAULT NULL,
  `accepted_term_policy_data` TEXT NULL DEFAULT NULL,
  `frontpage_id` INT NULL DEFAULT NULL,
  `system_unit_id` INT NULL DEFAULT NULL,
  `active` CHAR(1) NULL DEFAULT NULL,
  `custom_code` VARCHAR(256) NULL DEFAULT NULL,
  `otp_secret` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_program_idx` (`frontpage_id` ASC) VISIBLE,
  INDEX `sys_users_name_idx` (`name` ASC) VISIBLE,
  CONSTRAINT `system_users_ibfk_1`
    FOREIGN KEY (`frontpage_id`)
    REFERENCES `sysapae`.`system_program` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_user_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_user_group` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_group_group_idx` (`system_group_id` ASC) VISIBLE,
  INDEX `sys_user_group_user_idx` (`system_user_id` ASC) VISIBLE,
  CONSTRAINT `system_user_group_ibfk_1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `sysapae`.`system_users` (`id`),
  CONSTRAINT `system_user_group_ibfk_2`
    FOREIGN KEY (`system_group_id`)
    REFERENCES `sysapae`.`system_group` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_user_old_password`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_user_old_password` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `password` VARCHAR(256) NULL DEFAULT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_old_password_user_idx` (`system_user_id` ASC) VISIBLE,
  CONSTRAINT `system_user_old_password_ibfk_1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `sysapae`.`system_users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_user_program`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_user_program` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_program_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_program_program_idx` (`system_program_id` ASC) VISIBLE,
  INDEX `sys_user_program_user_idx` (`system_user_id` ASC) VISIBLE,
  CONSTRAINT `system_user_program_ibfk_1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `sysapae`.`system_users` (`id`),
  CONSTRAINT `system_user_program_ibfk_2`
    FOREIGN KEY (`system_program_id`)
    REFERENCES `sysapae`.`system_program` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_user_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_user_role` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_role_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_role_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_user_role_role_idx` (`system_role_id` ASC) VISIBLE,
  CONSTRAINT `system_user_role_ibfk_1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `sysapae`.`system_users` (`id`),
  CONSTRAINT `system_user_role_ibfk_2`
    FOREIGN KEY (`system_role_id`)
    REFERENCES `sysapae`.`system_role` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_user_unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_user_unit` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `system_unit_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_user_unit_user_idx` (`system_user_id` ASC) VISIBLE,
  INDEX `sys_user_unit_unit_idx` (`system_unit_id` ASC) VISIBLE,
  CONSTRAINT `system_user_unit_ibfk_1`
    FOREIGN KEY (`system_user_id`)
    REFERENCES `sysapae`.`system_users` (`id`),
  CONSTRAINT `system_user_unit_ibfk_2`
    FOREIGN KEY (`system_unit_id`)
    REFERENCES `sysapae`.`system_unit` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_wiki_page`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_wiki_page` (
  `id` INT NOT NULL,
  `system_user_id` INT NULL DEFAULT NULL,
  `created_at` VARCHAR(20) NULL DEFAULT NULL,
  `updated_at` VARCHAR(20) NULL DEFAULT NULL,
  `title` VARCHAR(256) NOT NULL,
  `description` VARCHAR(256) NULL DEFAULT NULL,
  `content` TEXT NOT NULL,
  `active` CHAR(1) NOT NULL DEFAULT 'Y',
  `searchable` CHAR(1) NOT NULL DEFAULT 'Y',
  `updated_by` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_wiki_page_user_idx` (`system_user_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_wiki_share_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_wiki_share_group` (
  `id` INT NOT NULL,
  `system_group_id` INT NULL DEFAULT NULL,
  `system_wiki_page_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_wiki_share_group_group_idx` (`system_group_id` ASC) VISIBLE,
  INDEX `sys_wiki_share_group_page_idx` (`system_wiki_page_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`system_wiki_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`system_wiki_tag` (
  `id` INT NOT NULL,
  `system_wiki_page_id` INT NOT NULL,
  `tag` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sys_wiki_tag_page_idx` (`system_wiki_page_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sysapae`.`specialities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`specialities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`professionals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`professionals` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `license` VARCHAR(45) NOT NULL,
  `contact` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`appointment_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`appointment_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`patients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`patients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `birth` DATE NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `rg` VARCHAR(20) NOT NULL,
  `cns` VARCHAR(15) NOT NULL,
  `sus` VARCHAR(15) NOT NULL,
  `responsible` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) NULL,
  `zip_code` VARCHAR(8) NOT NULL,
  `adress` VARCHAR(255) NOT NULL,
  `number` VARCHAR(5) NOT NULL,
  `complement` VARCHAR(100) NULL,
  `district` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `uf` VARCHAR(2) NOT NULL,
  `reference` VARCHAR(255) NULL,
  `patientscol` VARCHAR(45) NOT NULL,
  `observation` TEXT NULL,
  `active` VARCHAR(10) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `patientscol1` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`appointments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`appointments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `professionals_idprofessionals` INT NOT NULL,
  `appointment_type_idappointment_type` INT NOT NULL,
  `patients_idpatients` INT NOT NULL,
  `appointment_date` DATE NOT NULL,
  `notes` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`, `professionals_idprofessionals`, `appointment_type_idappointment_type`, `patients_idpatients`),
  INDEX `fk_appointments_professionals1_idx` (`professionals_idprofessionals` ASC) VISIBLE,
  INDEX `fk_appointments_appointment_type1_idx` (`appointment_type_idappointment_type` ASC) VISIBLE,
  INDEX `fk_appointments_patients1_idx` (`patients_idpatients` ASC) VISIBLE,
  CONSTRAINT `fk_appointments_professionals1`
    FOREIGN KEY (`professionals_idprofessionals`)
    REFERENCES `sysapae`.`professionals` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointments_appointment_type1`
    FOREIGN KEY (`appointment_type_idappointment_type`)
    REFERENCES `sysapae`.`appointment_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointments_patients1`
    FOREIGN KEY (`patients_idpatients`)
    REFERENCES `sysapae`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`patients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`patients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `birth` DATE NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `rg` VARCHAR(20) NOT NULL,
  `cns` VARCHAR(15) NOT NULL,
  `sus` VARCHAR(15) NOT NULL,
  `responsible` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) NULL,
  `zip_code` VARCHAR(8) NOT NULL,
  `adress` VARCHAR(255) NOT NULL,
  `number` VARCHAR(5) NOT NULL,
  `complement` VARCHAR(100) NULL,
  `district` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `uf` VARCHAR(2) NOT NULL,
  `reference` VARCHAR(255) NULL,
  `patientscol` VARCHAR(45) NOT NULL,
  `observation` TEXT NULL,
  `active` VARCHAR(10) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `patientscol1` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`disabilities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`disabilities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`professionals_has_specialities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`professionals_has_specialities` (
  `professionals_idprofessionals` INT NOT NULL,
  `specialities_idspecialities` INT NOT NULL,
  PRIMARY KEY (`professionals_idprofessionals`, `specialities_idspecialities`),
  INDEX `fk_professionals_has_specialities_specialities1_idx` (`specialities_idspecialities` ASC) VISIBLE,
  INDEX `fk_professionals_has_specialities_professionals1_idx` (`professionals_idprofessionals` ASC) VISIBLE,
  CONSTRAINT `fk_professionals_has_specialities_professionals1`
    FOREIGN KEY (`professionals_idprofessionals`)
    REFERENCES `sysapae`.`professionals` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_professionals_has_specialities_specialities1`
    FOREIGN KEY (`specialities_idspecialities`)
    REFERENCES `sysapae`.`specialities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sysapae`.`patients_has_disabilities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sysapae`.`patients_has_disabilities` (
  `patients_idpatients` INT NOT NULL,
  `disabilities_iddisabilities` INT NOT NULL,
  PRIMARY KEY (`patients_idpatients`, `disabilities_iddisabilities`),
  INDEX `fk_patients_has_disabilities_disabilities1_idx` (`disabilities_iddisabilities` ASC) VISIBLE,
  INDEX `fk_patients_has_disabilities_patients1_idx` (`patients_idpatients` ASC) VISIBLE,
  CONSTRAINT `fk_patients_has_disabilities_patients1`
    FOREIGN KEY (`patients_idpatients`)
    REFERENCES `sysapae`.`patients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_patients_has_disabilities_disabilities1`
    FOREIGN KEY (`disabilities_iddisabilities`)
    REFERENCES `sysapae`.`disabilities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
