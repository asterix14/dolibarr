<?php
/* Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 		\file   	htdocs/compta/resultat/bilan.php
 * 		\ingroup    compta
 * 		\brief  	Fichier page bilan compta
 */

require('../../main.inc.php');
require_once(DOL_DOCUMENT_ROOT."/compta/tva/class/tva.class.php");
require_once(DOL_DOCUMENT_ROOT."/compta/sociales/class/chargesociales.class.php");

if (!$user->rights->compta->resultat->lire)
  accessforbidden();


/*
 *	Views
 */

llxHeader();

$year=$_GET["year"];
$month=$_GET["month"];
if (! $year) { $year = strftime("%Y", time()); }


/* Le compte de r�sultat est un document officiel requis par l'administration selon le status ou activit� */

print_titre("Bilan".($year?" ann�e $year":""));

print '<br>';

print "Cet �tat n'est pas disponible.";


$db->close();

llxFooter();
?>
