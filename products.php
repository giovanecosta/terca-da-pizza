<?php

$model = [
	'Category' => 'PREMIUM',
	'CategoryType' => 'PIZZA',
	'Egroup' => '109',
	'Name' => 'FRANGO PHILADÉLPHIA',
	'PhotoCart' => '14453734615626a6151ceee8.74131617.png',
	'Recipes' => '',
	'category' => 'PREMIUM',
	'category_type' => 'PIZZA',
	'egroup_id' => '109',
	'ephoto' => '144975385456697cfe1fdb90.75420140.png',
	'id' => '10430',
	'newProd' => '1',
	'nickname' => 'FRANGO PHILADÉLPHIA',
	'recipe' => null
];

$recipes = [
	'bahianinha' => 'mussarela, calabresa moída, cebola e pimenta.',
	'banana' => 'mussarela, banana, açúcar e canela',
	'escarola' => 'escarola, bacon, cobertura de mussarela e orégano',
	'calabresa' => 'essa você já sabe... (com cebola)',
	'brasileira' => 'mussarela, presunto e ovos',
	'frango' => 'mussarela, frango, azeitonas e orégano',
	'americana' => 'presunto e cobertura de mussarela',
	'milho' => 'mussarela e... isso mesmo, milho!',
	'hotdog' => 'WTF?! salsicha, mussarela e batata palha',
	'chocolate' => 'chocolate com castanha de caju',
	'frango_com_catupiry' => 'Frango desfiado, catupiry, azeitona e orégano.',
	'5_queijos' => 'Molho, mussarela, queijo da roça, parmesão, queijo prato, azeitona e orégano.',
	'atum' => 'Molho, mussarela, atum, cebola e orégano',
	'portuguesa' => 'Molho, mussarela, presunto, cebola, ervilha, milho, ovos, azeitona e orégano.',
];

$products = [];

$i = 1;
foreach (glob('flavours/*.jpg') as $arquivo) {
    $x = $model;
    $name = explode('.', basename($arquivo));
    $name = $name[0];
    $pretty_name = ucwords(str_replace('_', ' ', $name));

    $x['id'] = $i++;
    $x['Name'] = $pretty_name;
    $x['nickname'] = $pretty_name;
    $x['ephoto'] = basename($arquivo);
    if (isset($recipes[$name])){
    	$x['Recipes'] = ucfirst($recipes[$name]);
    } else {
    	$x['Recipes'] = 'Ingredientes secretos.';
    }

    $products[] = $x;
}

echo json_encode($products);