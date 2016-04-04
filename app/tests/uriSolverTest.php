<?php

namespace tests;

//Doit être utilisé avec la commande php en console.
require_once './../../vendor/autoload.php';

use urisolver\UriParser;
use urisolver\UriSolver;

function printUriPiecesTypes($uriPieces)
{
    $str = '';
    foreach ($uriPieces as $uriPieceContainer) {
        $str .= '/'.$uriPieceContainer['piece'].':'.$uriPieceContainer['type'];
    }

    return $str."\n";
}

function matche($uriRoute, $uri)
{
    $uriSalver = new UriSolver($uri);

    return $uriSalver->matche($uriRoute) ? 'true' : 'false';
}

$mask = "%32s ==> %60s\n";
echo "UriParser:parse('url') Method test calls: \n";
printf($mask, '/', printUriPiecesTypes(UriParser::parse('/')));
printf($mask, '/cartes', printUriPiecesTypes(UriParser::parse('/cartes')));
printf($mask, '/cartes/ajouter', printUriPiecesTypes(UriParser::parse('/cartes/ajouter')));
printf($mask, '/cartes/356', printUriPiecesTypes(UriParser::parse('/cartes/356')));
printf($mask, '/cartes/un/modifier', printUriPiecesTypes(UriParser::parse('/cartes/un/modifier')));
printf($mask, '/cartes/./supprimer', printUriPiecesTypes(UriParser::parse('/cartes/./supprimer')));
printf($mask, '/cartes/{idCarte:INT}/supprimer', printUriPiecesTypes(UriParser::parse('/cartes/{idCarte:INT}/supprimer')));

printf($mask, '/{username : STRING }/cartes', printUriPiecesTypes(UriParser::parse('/{username : STRING }/cartes')));
printf($mask, '/1/cartes', printUriPiecesTypes(UriParser::parse('/1/cartes')));
printf($mask, '/3/cartes', printUriPiecesTypes(UriParser::parse('/3/cartes')));
printf($mask, '/1.0/cartes', printUriPiecesTypes(UriParser::parse('/1.0/cartes')));
printf($mask, '/truc/cartes', printUriPiecesTypes(UriParser::parse('/truc/cartes')));
printf($mask, "/bi'dule/cartes", printUriPiecesTypes(UriParser::parse("/bi'dule/cartes")));

echo "new UriSolver('url')->matche('route') Method test calls: \n";

$mask = "%32s     matche     %32s [%s]\n";
printf($mask, '/', '/', matche('/', '/'));
printf($mask, '/cartes', '/cartes', matche('/cartes', '/cartes'));
printf($mask, '/cartes/ajouter', '/cartes/ajouter', matche('/cartes/ajouter', '/cartes/ajouter'));
printf($mask, '/cartes/356', '/cartes/356', matche('/cartes/356', '/cartes/356'));
printf($mask, '/cartes/un/modifier', '/cartes/un/modifier', matche('/cartes/un/modifier', '/cartes/un/modifier'));
printf($mask, '/cartes/./supprimer', '/cartes/./supprimer', matche('/cartes/./supprimer', '/cartes/./supprimer'));
printf($mask, '/cartes/{idCarte:INT}/supprimer', '/cartes/{id_carte:INT}/supprimer', matche('/cartes/{idCarte:INT}/supprimer', '/cartes/{id_carte:INT}/supprimer'));
printf($mask, '/{username : STRING }/cartes', '/{username : STRING }/cartes', matche('/{username : STRING }/cartes', '/{username : STRING }/cartes'));
printf($mask, '/1/cartes', '/1/cartes', matche('/1/cartes', '/1/cartes'));

printf($mask, '/cartes/{idCarte:INT}/supprimer', '/cartes/325/supprimer', matche('/cartes/{idCarte:INT}/supprimer', '/cartes/325/supprimer'));
printf($mask, '/cartes/{idCarte:INT}/supprimer', '/cartes/10/supprimer', matche('/cartes/{idCarte:INT}/supprimer', '/cartes/10/supprimer'));
printf($mask, '/cartes/{idCarte:INT}/supprimer', '/cartes/truc/supprimer', matche('/cartes/{idCarte:INT}/supprimer', '/cartes/truc/supprimer'));

printf($mask, '/{username : STRING }/cartes', '/10/cartes', matche('/{username : STRING }/cartes', '/10/cartes'));
printf($mask, '/{username : STRING }/cartes', '/anonymous/cartes', matche('/{username : STRING }/cartes', '/anonymous/cartes'));
printf($mask, '/{username : STRING }/cartes', '/utilisateur/cartes', matche('/{username : STRING }/cartes', '/utilisateur/cartes'));

printf($mask, '/cartes/{idCarte:INT}', '/cartes/1', matche('/cartes/{idCarte:INT}', '/cartes/1'));
