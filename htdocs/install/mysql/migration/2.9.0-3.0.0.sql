--
-- $Id$
--
-- Be carefull to requests order.
-- This file must be loaded by calling /install/index.php page
-- when current version is 2.8.0 or higher. 
--
-- To add a column:         ALTER TABLE llx_table ADD COLUMN newcol varchar(60) NOT NULL DEFAULT '0' AFTER existingcol;
-- To rename a column:      ALTER TABLE llx_table CHANGE COLUMN oldname newname varchar(60);
-- To change type of field: ALTER TABLE llx_table MODIFY name varchar(60);
--

-- Fix bad old data
UPDATE llx_bank_url SET type='payment' WHERE type='?' AND label='(payment)' AND url LIKE '%compta/paiement/fiche.php%';

-- Add recuperableonly field
ALTER TABLE llx_product       add column recuperableonly integer NOT NULL DEFAULT '0' after tva_tx;
ALTER TABLE llx_product_price add column recuperableonly integer NOT NULL DEFAULT '0' after tva_tx;

-- Rename envente into tosell and add tobuy
ALTER TABLE llx_product CHANGE column envente tosell tinyint DEFAULT 1;
ALTER TABLE llx_product add column tobuy tinyint DEFAULT 1 after tosell;
ALTER TABLE llx_product_price CHANGE column envente tosell tinyint DEFAULT 1;
 
ALTER TABLE llx_bank MODIFY column fk_type varchar(6);

ALTER TABLE llx_boxes_def DROP INDEX uk_boxes_def;
ALTER TABLE llx_boxes_def MODIFY file varchar(200) NOT NULL;
ALTER TABLE llx_boxes_def MODIFY note varchar(130);
ALTER TABLE llx_boxes_def ADD UNIQUE INDEX uk_boxes_def (file, entity, note);

ALTER TABLE llx_notify_def MODIFY fk_contact integer NULL;
ALTER TABLE llx_notify_def ADD COLUMN fk_user integer NULL after fk_contact;
ALTER TABLE llx_notify_def ADD COLUMN type varchar(16) DEFAULT 'email';

ALTER TABLE llx_notify MODIFY fk_contact integer NULL;
ALTER TABLE llx_notify ADD COLUMN fk_user integer NULL after fk_contact;
ALTER TABLE llx_notify ADD COLUMN type varchar(16) DEFAULT 'email';

ALTER TABLE llx_actioncomm MODIFY label varchar(128) NOT NULL;

ALTER TABLE llx_expedition MODIFY date_expedition datetime;
ALTER TABLE llx_expedition MODIFY date_delivery datetime NULL;

ALTER TABLE llx_societe ADD COLUMN canvas varchar(32) DEFAULT NULL AFTER default_lang;

ALTER TABLE llx_cond_reglement RENAME TO llx_c_payment_term;
ALTER TABLE llx_expedition_methode RENAME TO llx_c_shipment_mode;

ALTER TABLE llx_facturedet_rec ADD COLUMN special_code integer UNSIGNED DEFAULT 0 AFTER total_ttc;
ALTER TABLE llx_facturedet_rec ADD COLUMN rang integer DEFAULT 0 AFTER special_code;

ALTER TABLE llx_actioncomm ADD COLUMN fk_supplier_order   integer;
ALTER TABLE llx_actioncomm ADD COLUMN fk_supplier_invoice integer;


ALTER TABLE llx_tmp_caisse MODIFY fk_article integer NOT NULL;

ALTER TABLE llx_propaldet ADD COLUMN fk_parent_line	integer NULL AFTER fk_propal;
ALTER TABLE llx_commandedet ADD COLUMN fk_parent_line integer NULL AFTER fk_commande;
ALTER TABLE llx_facturedet ADD COLUMN fk_parent_line integer NULL AFTER fk_facture;
ALTER TABLE llx_facturedet_rec ADD COLUMN fk_parent_line integer NULL AFTER fk_facture;

--Remove old Spanish TVA
UPDATE llx_c_tva SET taux = '18' WHERE rowid = 41;
UPDATE llx_c_tva SET taux = '8' WHERE rowid = 42;
DELETE FROM llx_c_tva WHERE rowid = 45;
DELETE FROM llx_c_tva WHERE rowid = 46;


ALTER TABLE llx_adherent  ADD COLUMN import_key varchar(14);
ALTER TABLE llx_categorie ADD COLUMN import_key varchar(14);


ALTER TABLE llx_product ADD COLUMN customcode varchar(32) after note;
ALTER TABLE llx_product ADD COLUMN fk_country integer after customcode; 


ALTER TABLE llx_ecm_directories ADD UNIQUE INDEX idx_ecm_directories (label, fk_parent, entity);
ALTER TABLE llx_ecm_documents ADD UNIQUE INDEX idx_ecm_documents (fullpath_dol);

--Add modules facture fournisseur
insert into llx_const (name, value, type, note, visible) values ('INVOICE_SUPPLIER_ADDON_PDF', 'canelle','chaine','',0);
ALTER TABLE llx_facture_fourn ADD COLUMN model_pdf varchar(50) after note_public;

create table llx_c_ziptown
(
  rowid				integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code				varchar(5) DEFAULT NULL,
  fk_county			integer NOT NULL,
  zip	 			varchar(10) NOT NULL,
  town				varchar(255) NOT NULL,
  active 			tinyint NOT NULL DEFAULT 1
)type=innodb;

ALTER TABLE llx_c_ziptown ADD INDEX idx_c_ziptown_fk_county (fk_county);
ALTER TABLE llx_c_ziptown ADD CONSTRAINT fk_c_ziptown_fk_county		FOREIGN KEY (fk_county)   REFERENCES llx_c_departements (rowid);

ALTER TABLE llx_socpeople ADD COLUMN canvas	varchar(32)	DEFAULT 'default' after default_lang;
ALTER TABLE llx_socpeople MODIFY canvas	varchar(32)	DEFAULT 'default';

UPDATE llx_socpeople SET canvas = 'default' WHERE canvas = 'default@contact';
UPDATE llx_societe SET canvas = 'default' WHERE canvas = 'default@thirdparty';
UPDATE llx_societe SET canvas = 'individual' WHERE canvas = 'individual@thirdparty';

insert into llx_const (name, value, type, note, visible) values ('MAIN_DELAY_SUPPLIER_ORDERS_TO_PROCESS','7','chaine','Tolérance de retard avant alerte (en jours) sur commandes fournisseurs non traitées',0);