<?php

$today_is_not_tuesday = ['hoje', 'não', 'é', 'terça'];
shuffle($today_is_not_tuesday);

$fp = !empty($_GET['from_pirata']);

$messages_only_tuesday = [
  'Só rola na Terça... Volta na Terça...',
  'Tem calendário em casa não?! Hoje náo é terça, amiguinho!',
  'Volta aqui terça que vai ter várias pizza cheirosa e gostosa procê, seu lindo! :3 <br /><br /><i>P.S.: Ou Linda. E maravilhosa, também, no caso. :3 :3',
  'A fome é muita, eu sei, mas a terça é só uma. E não é hoje... :/',
  'Fecho os olhos pra não ver passar o tempo. Sinto falta de você, terça-feira.',
  '99% uma fome danada, mas aquele 1% ainda não chegou na terça-feira.',
  'Você Perdeu o jogo. E a pizza também. Mas terça tem! :D',
  ucfirst(implode($today_is_not_tuesday, ' ')).'...',
  'Chega '.((int) date('Y') + 1).' mas não chega terça? #mimimi',
  'A paciência é uma virtude. Calma que a terça já chega.',
  '#naovaiterpizza',
  'Terça ainda não chegou, mas você pode fazer a sua: '. ($fp ? '' : '<a href="https://www.youtube.com/watch?v=Z--jYTc8FqM">' ) .'https://www.youtube.com/watch?v=Z--jYTc8FqM'. ($fp ? '' : '</a>' ),
  'Estudos afirmam que a cada 10 pizzas, metade são 5! Mas hoje não vai ter nenhuma... :/',
  'Hoje não é terça mas, se você tiver vendo essa messagem, clique '. ($fp ? '' : '<a href="' ) . 'http://tdp.redealumni.lan/ultima_chance.php'. ($fp ? '' : '" >aqui</a>'). ' pra ganhar pizza hoje também!',
  'Guarapari, Búzios, minha arte. Juliana?! Katrina?! 16, 18. Jarbas, o meu pai?! O MEU PAI?! Pizza?! Seu sonho... xD',
  'Hoje é terça! Dia de comer uma pizza! #sqn',
  'Terça é um dia como todos os outros. Só que terça.',
];

$messages_closed = [
  'Ihhh, já era! Quem pediu, pediu. Quem não pediu não pede mais!',
  'Pista fechada. Espero que você tenha feito seu pedido, Rubinho!',
  '"Você deixou ela de lado pra falar com seus amigos sobre suas coisas chatas. Bateu a hora e os pedidos fecharam, agora vê se espera a próxima terça, parça." - Charlie Brown Jr.',
  'Foi namorar, perdeu o lugar... e a Pizza.',
  'Foi na roça, perdeu a carroça... e a Pizza.',
  'Era uma vez um pedido... Agora é foco na próxima terça!',
  'Tá titi? Fica titi não... A próxima terça vem logo aí!',
  'Hoje não! Hoje não! Hoje sim? Hoje sim... :/',
  'Pedidos fechados, revisados, carimbados, homologados e enviados. Até a próxima.',
  'Sempre dando mole, né? Mas cá entre nós: aposto que se você pegar um pedaço, ninguém vai desconfiar... OU VAI HAHAHA! Boa sorte. :)',
  '"Tente outra vez." - Raul Seixas. "Mas agora só terça que vem! AUAU!" - Pirata',
  'E agora você vem, né? Agora você quer? Ha! Só que agora já fechou, volta aqui terça que vem.',
  'Chola mais, quem sabe alguém fica com pena e te dá um pedaço.',
  'Então me ajude a segurar essa barra que é ficar sem comer.',
  '"Chegou cedo pra terça que vem! rsrsrs" - Tio do Pavê.',
  'A pizza já era mas relaxa que tem ovo na geladeira! ;D',
  'Parabéns! Vai manter a dieta com sucesso!',
  'Os pedidos já fecharam mas, se você tiver vendo essa messagem, clique rápido '. ($fp ? '' : '<a href="' ) . 'http://tdp.redealumni.lan/ultima_chance.php'. ($fp ? '' : '" >aqui</a>'). ' que a gente dá um jeitinho!',
];
