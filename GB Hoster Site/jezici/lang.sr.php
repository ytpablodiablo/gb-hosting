<?php   
	$brmasine = query_numrows("SELECT * FROM `box`");
	$brserveri = query_numrows("SELECT * FROM `serveri`");
        $brtiket = query_numrows("SELECT * FROM `tiketi` WHERE `status` = '1'");
	
	$masine = mysql_query("SELECT * FROM `box`");	
	$igraci = 0;

	while($row = mysql_fetch_assoc($masine)) {
		$masina = query_fetch_assoc("SELECT * FROM `box` WHERE `boxid` = '{$row[boxid]}'");
		$cache = unserialize(gzuncompress($masina['cache']));
		$igraci = $igraci + $cache["{$masina['boxid']}"]['players']['players'];
	}

	// header.php - START -
	$jezik['text0']      = 'Registruj se';
	$jezik['text1']      = 'Prijavi grešku';
	$jezik['text2']      = 'Izloguj se';
	$jezik['text3']      = 'Vaše korisničko ime';
	$jezik['text4']      = 'Vaša lozinka';
	$jezik['text5']      = 'Zapamti me';
	$jezik['text6']      = 'Zaboravljena lozinka';
	$jezik['text7']      = 'Početna <p id="mtekst">strana</p>';
	$jezik['text8']      = 'Korisnički <p id="mtekst">panel</p>';
	$jezik['text9']      = 'Naruči <p id="mtekst">server</p>';
	$jezik['text10']     = 'Game <p id="mtekst">panel</p>';
	$jezik['text11']     = 'Forum <p id="mtekst">hostinga</p>';
	$jezik['text12']     = 'Kontakt <p id="mtekst">stranica</p>';
	$jezik['text13']     = 'Pregled profila';
	$jezik['text14']     = 'Billing';
	$jezik['text15']     = 'Podešavanja';
	$jezik['text16']     = 'Logovi';
	$jezik['text16s']     = 'SMS Logovi';
	$jezik['text16sa']     = 'SMS Billing ADD';
	$jezik['text17']     = 'Početna';
	$jezik['text18']     = 'Serveri';
	$jezik['text19']     = 'Podrška';
	$jezik['text20']     = 'server';
	$jezik['text21']     = 'webftp';
	$jezik['text22']     = 'Admini';
	$jezik['text23']     = 'Plugini';
	$jezik['text24']     = 'Modovi';
	$jezik['text25']     = 'Reinstaliraj';
	$jezik['text26']     = 'Boost';
	$jezik['text27']     = 'Rcon';
	$jezik['text28']     = 'Start';
	$jezik['text29']     = 'Stop';
	$jezik['text30']     = 'Restart';
	// header.php - END -

	// index.php - START -
	$jezik['text31']     = 'Skini najbolji <z>cs 1.6</z>';
	$jezik['text32']     = 'Skidanja';
	$jezik['text33']     = 'Naruči odmah';
	$jezik['text34']     = 'LITE I PREMIUM SERVERI';
	$jezik['text35']     = 'Uskoro';
	$jezik['text36']     = '5 dana garancija';
	$jezik['text37']     = 'Ukoliko vam se ne bude sviđao rad vašeg servera i hostinga, a nije prošlo 5 dana od dana uplate možete zatražiti povraćaj novca na <a href="mailto:support@gb-hoster.mE">support@gb-hoster.me</a>';
	$jezik['text38']     = 'Serveri sa antiddos zaštitom';
	$jezik['text39']     = 'Svi serveri imaju automatski anti-DOSS.';
	$jezik['text40']     = 'Dobar kvalitet za nisku cenu';
	$jezik['text41']     = 'Dedicated server sa lokacijom u Nemackoj, svaki server ima nizak ping.';
	$jezik['text42']     = 'Podrska 24h';
	$jezik['text43']     = 'Nasa podrska je tu dan i noc za svaki vas problem.';
	$jezik['text44']     = $brserveri.'<p id="title">servera<div id="h1">Do sada smo prodali <z>'.$brserveri.'</z><br/> servera, koji uspešno rade</div></p>';
	$jezik['text45']     = $brmasine.'<p id="title">mašine<div id="h1">Naše servere hostujemo na <z>'.$brmasine.'</z> dedicated servera velikog kapaciteta, čime<br/> garantujemo igru bez laga</div></p>';
	$jezik['text46']     = $igraci.'<p id="title2">igrača<div id="h2">Koji su zadovoljni našim<br/>serverima, i koji iz dana u<br/>dan rastu.</div></p>';
	// index.php - END -

	// process.php - START -
	$jezik['text47']     = 'Ova akcija nije dozvoljena na demo nalogu!';
	$jezik['text48']     = 'Promena avatara';
	$jezik['text49']     = 'Morate uneti najmanje 5 karaktera.';
	$jezik['text50']     = 'Dozvoljeno je najviše 1000 karaktera!';
	$jezik['text51']     = 'Postavljanje komentara na';
	$jezik['text52']     = 'zabranjeno vam je korišćenje tiketa do';
	$jezik['text53']     = 'Morate sačekati jos';
	$jezik['text54']     = 'sekundi';
	$jezik['text55']     = 'Dozvoljeno je najviše 200 karaktera!';
	$jezik['text56']     = 'Postavljanje odgovora u tiket';
	$jezik['text57']     = 'Ovaj fajl nije u dozvoljenom formatu.';
	$jezik['text58']     = 'Maksimalna veličina slike je 2 MB.';
	$jezik['text59']     = 'Niste uneli ime i prezime.';
	$jezik['text60']     = 'Vase ime i prezime nije pravilno.';
	$jezik['text61']     = 'Niste uneli iznos uplate.';
	$jezik['text62']     = 'Novac mora biti u brojnom formatu';
	$jezik['text63']     = 'Niste uneli broj racuna.';
	$jezik['text64']     = 'Br. racuna mora biti u brojnom formatu';
	$jezik['text65']     = 'Niste uneli datum uplate.';
	$jezik['text66']     = 'Niste uneli slike uplatnice.';
	$jezik['text67']     = 'Dodavanje uplate';
	$jezik['text68']     = 'Izlogovali ste se.';
	$jezik['text69']     = 'Nisi ulogovan.';
	$jezik['text60']     = 'Morate izabrati lokaciju game servera.';
	$jezik['text71']     = 'Morate izabrati broj slotova.';
	$jezik['text72']     = 'Morate izabrati na koliko meseci placate.';
	$jezik['text73']     = 'Ne možete naručiti više od 5 servera, pritom da ih niste platili.';
	$jezik['text74']     = 'Uspešno ste otkazali narudžbinu.';
	$jezik['text75']     = 'Server je vec uplaćen.';
	$jezik['text76']     = 'Uspešno ste uplatili server.';
	$jezik['text77']     = 'Nemate dovoljno novca da uplatite server.';
	$jezik['text78']     = 'Taj server nije vaš.';
	$jezik['text79']     = 'Server nije uplacen.';
	$jezik['text80']     = 'Server je instaliran i nemozete povratiti novac.';
	$jezik['text81']     = 'Uspešno ste povratili novac.';
	$jezik['text82']     = 'Port mora biti u brojnom formatu';
	$jezik['text83']     = 'Ugovor mora biti u brojnom formatu';
	$jezik['text84']     = 'Slotovi moraju biti u brojnom formatu';
	$jezik['text85']     = 'Igra mora biti u brojnom formatu';
	$jezik['text86']     = 'ID klijenta mora biti u brojnom formatu';
	$jezik['text87']     = 'Ip id mora biti u brojnom formatu';
	$jezik['text88']     = 'Box id mora biti u brojnom formatu';
	$jezik['text89']     = 'Server id mora biti u brojnom formatu';
	$jezik['text90']     = 'Taj port je vec u upotrebi, javite administratorima za ovu gresku!';
	$jezik['text91']     = 'Uspešno ste instalirali server.';
	$jezik['text92']     = 'Taj server nije istekao.';
	$jezik['text93']     = 'Produženje servera';
	$jezik['text94']     = 'Uspešno ste produzili server.';
	$jezik['text95']     = 'Nemate dovoljno novca da uplatite server.';
	$jezik['text96']     = 'Sigurnosni kod mora biti napisan u brojevima!';
	$jezik['text97']     = 'Sigurnosni kod mora sadrzati 5 cifara.';
	$jezik['text98']     = 'Netacan sigurnosni kod!';
	$jezik['text99']     = 'Morate uneti naslov tiketa!';
	$jezik['text100']    = 'Morate izabrati neki server!';
	$jezik['text101']    = 'Morate izabrati vrstu tiketa!';
	$jezik['text102']    = 'Morate izabrati prioritet tiketa!';
	$jezik['text103']    = 'Morate napisati nesto u tiketu!';
	$jezik['text104']    = 'Server ID mora biti u brojevnom formatu!';
	$jezik['text105']    = 'Naslov tiketa moze sadrzati najvise 30 karaktera.';
	$jezik['text106']    = 'Naslov tiketa mora sadrzati najmanje 4 karaktera.';
	$jezik['text107']    = 'Tekst moze sadrzati najvise 1000 karaktera.';
	$jezik['text108']    = 'Tekst mora sadrzati najmanje 20 karaktera.';
	$jezik['text109']    = 'Tiket ID mora biti u brojevnom formatu.';
	$jezik['text110']    = 'Uspešno ste zakljucali tiket.';
	$jezik['text111']    = 'Uspešno ste otkljucali tiket.';
	$jezik['text112']    = 'Morate sacekati 5 minuta pre sledece prijave.';
	$jezik['text113']    = 'Naslov mora sadrzati najmanje 5 karaktera.';
	$jezik['text114']    = 'Tekst mora sadrzati najmanje 10 karaktera.';
	$jezik['text115']    = 'Naslov moze sadrzati najvise 30 karaktera.';
	$jezik['text116']    = 'Tekst moze sadrzati najvise 30 karaktera.';
	$jezik['text117']    = 'Već ste dali reputaciju ovom korisniku za ovaj tiket.';
	$jezik['text118']    = 'Već ste dali reputaciju ovom korisniku za ovaj tiket.';
	$jezik['text119']    = 'Duzina imena fajla ne sme biti veca od 24 slova.';
	$jezik['text120']    = 'Duzina imena fajla mora biti veca od 3 slova.';
	$jezik['text121']    = 'Ne mogu se konektovati na FTP servera!';
	$jezik['text122']    = 'Ne mogu napraviti folder.';
	$jezik['text123']    = 'Ne mogu izbrisati folder.';
	$jezik['text124']    = 'Ne mogu izbrisati fajl.';
	$jezik['text125']    = 'To ime fajla/foldera vec postoji ili se dogodila neka greska.';
	$jezik['text126']    = 'Taj format nije dozvoljen.';
	$jezik['text128']    = 'Fajl moze biti najvise 32mb.';
	$jezik['text129']    = 'Uspesno ste uploadovali fajl.';
	$jezik['text130']    = 'Ne mogu uploadati fajl.';
	$jezik['text131']    = 'Ne mogu spremiti fajl!';
	$jezik['text132']    = 'Nemate pristup ovom serveru.';
	$jezik['text133']    = 'Moguće rešenje i greška: <z>Server je startovan ali nije online. Proverite da li je default mapa ispravna i da li postoji. Ako je ispravna onda izbrišite zadnji plugin koji ste dodali.';
	$jezik['text134']    = 'Rešenje: <z>Server je ugašen, da bi ga pokrenuli morate ga startovati klikom na dugme start.';
	$jezik['text135']    = 'Korisničko ime je prekratko (5 slova najmanje)';
	$jezik['text136']    = 'Takvo korisničko ime već postoji.';
	$jezik['text137']    = 'Niste uneli vase ime i prezime';
	$jezik['text138']    = 'Vase ime i prezime nije pravilno.';
	$jezik['text139']    = 'Morate potvrditi šifru.';
	$jezik['text140']    = 'Niste dobro potvrdili šifru.';
	$jezik['text141']    = 'Šifra je veoma slaba.';
	$jezik['text142']    = 'Niste uneli e-mail adresu.';
	$jezik['text143']    = 'Unesite ispravan e-mail.';
	$jezik['text144']    = 'Takav E-mail je već u upotrebi.';
	$jezik['text145']    = 'Sigurnosni kod nije tacan.';
	$jezik['text146']    = 'Niste izabrali zemlju.';
	$jezik['text147']    = 'Niste uneli ime i prezime.';
	$jezik['text148']    = 'Niste uneli email adresu.';
	$jezik['text149']    = 'Niste uneli username.';
	// process.php - END -

	// login_process.php - START -
	$jezik['text150']     = 'Uspešno ste se ulogovali na demo nalog.';
	$jezik['text151']     = 'Pogrešili ste lozinku 5 puta, sačekajte 10 minuta pa pokušajte opet!';
	$jezik['text152']     = 'Niste aktivirali nalog. Idite na e-mail i aktivirajte nalog.';
	$jezik['text153']     = 'Neko je već ulogovan na ovaj nalog.';
	$jezik['text154']     = 'Uspešan login.';
	$jezik['text155']     = 'Vaš nalog je suspendovan.';
	$jezik['text156']     = 'Pogrešili ste lozinku 5 puta, sačekajte 10 minuta pa pokušajte opet!';
	$jezik['text157']     = 'Netačno korisničko ime ili lozinka.';
	$jezik['text158']     = 'Niste popunili sva polja za login.';
	$jezik['text159']     = 'Morate se izlogovati da bi mogli da se ulogujete';
	$jezik['text160']     = 'Banovan na 10 minuta zbog 5 neuspešnih pokušaja';
	$jezik['text161']     = 'Pogrešan login. Pokušaj:';
	// login_process.php - END -

	// regprocess.php - START -
	$jezik['text162']     = 'Niste uneli korisnicko ime.';
	$jezik['text163']     = 'Korisničko ime je prekratko (5 slova najmanje)';
	$jezik['text164']     = 'Takvo korisničko ime već postoji.';
	$jezik['text165']     = 'Niste uneli vase ime i prezime';
	$jezik['text166']     = 'Vase ime i prezime nije pravilno.';
	$jezik['text167']     = 'Šifra je veoma slaba.';
	$jezik['text168']     = 'Niste uneli e-mail adresu.';
	$jezik['text169']     = 'Unesite ispravan e-mail.';
	$jezik['text170']     = 'Takav E-mail je već u upotrebi.';
	$jezik['text171']     = 'Registracija je privremeno iskljucena.';
	$jezik['text172']     = 'Registracija naloga.';
	$jezik['text173']     = 'Pozdrav';
	$jezik['text174']     = 'Nedavno ste se registrovali na <b>GB Hoster</b>.<br />Da bi zavrsili registraciju potrebno je da kliknete na link ispod:';
	$jezik['text175']     = 'Sigurnosni kod';
	$jezik['text176']     = 'Ovaj kod nemožete menjati pa vam preporučujemo da ga zapamtite jer vam je potreban.';
	$jezik['text177']     = 'Ne odgovarajte na ovu poruku, ovo je samo informativna poruka!';
	$jezik['text178']     = 'Ne mogu poslati e-mail adresu.';
	$jezik['text179']     = 'Sigurnosni kod nije tačan.';
	$jezik['text180']     = 'Taj klijent ne postoji.';
	$jezik['text181']     = 'Morate uneti kod aktivacije.';
	$jezik['text182']     = 'ID klijenta mora biti u brojevnom formatu.';
	$jezik['text183']     = 'Taj klijent ne postoji.';
	$jezik['text184']     = 'Vaš nalog je već aktiviran!';
	$jezik['text185']     = 'Uspešno ste aktivirali nalog. Sada se prijavite.';
	$jezik['text186']     = 'Kod aktivacije nije tačan!';
	// regprocess.php - END -

	// footer.php - START -
	$jezik['text187']     = 'Linkovi';
	$jezik['text188']     = 'Pomoc';
	$jezik['text189']     = 'Aktivacija naloga';
	$jezik['text190']     = '<z>Registracije je uspešno obavljena.<br />Da bi završili registraciju potrebno je da Vam admin odobri<br />registraciju ili da odete na vaš e-mail nalog i <br />da udjete na e-mail sa naslovom "Aktiviranje naloga."<br />U taj e-mail videćete link koji je poslat i kliknućete na njega.<br />Kada kliknete vaša registracija je uspešna i možete se ulogovati.</z><br />';
	$jezik['text191']     = 'U redu';
	$jezik['text192']     = 'Korisničko ime.';
	$jezik['text193']     = 'Vaše puno ime i prezime.';
	$jezik['text194']     = 'Validan e-mail.';
	$jezik['text195']     = 'Lokacija.';
	$jezik['text196']     = 'Šifra (<z>Ostavite prazno polje za random</z>).';
	$jezik['text269']     = 'Šifra';
	$jezik['text197']     = 'Proveravamo da li ste mašina ili čovek.';
	$jezik['text198']     = 'Ostalo';
	$jezik['text199']     = 'Otkaži';
	$jezik['text200']     = 'Informacije';
	$jezik['text201']     = 'ovo su informacije za uplatu određene svote novca na računu.<br />Molimo vas da izaberete državu za koju hoćete obaviti uplatu.';
	$jezik['text202']     = 'Izaberite državu';
	$jezik['text203']     = '<z>Narudžbina je gotova.</z><br />
				<z>Da bi uplatili svoj server morate ići na billing i dodati uplatu ( <a href="ucp-billingadd.php" target="_blank"><font color="red">LINK</font></a> )<br />
				u iznosu ovog naručenog servera ( možete dodati i više, ako želite ).<br />
				Kada uplatite idite na <a href="naruci.php" target="_blank"><font color="red">Naruči server</font></a> pa na <a href="naruci-zahtev.php" target="_blank"><font color="red">Zahtev za dizanje</font></a>.<br />
				Pritom kliknite na "Uplati server</z>" zatim kada se refresh stranica<br />
				kliknite na "Instaliraj server</z>", popunite podatke i imaćete svoj server.</z><br />';
	$jezik['text204']     = 'Ime servera';
	$jezik['text205']     = 'Sačuvaj';
	$jezik['text206']     = 'Default mapa';
	$jezik['text207']     = '<b>Napomena:</b> Ova izmena ce biti aktivna posle restarta<Br />
				   servera. Ukoliko promenite default mapu i posle restarta<br />
				   vam ne radi server onda mapa nije ispravna ili ne postoji!';
	$jezik['text208']     = 'Unesite vaš sigurnosni kod.';
	$jezik['text209']     = 'Ime foldera';
	$jezik['text210']     = 'Dodaj novi folder';
	$jezik['text211']     = 'Unesite ime foldera';
	$jezik['text212']     = 'Dodaj';
	$jezik['text213']     = 'Promeni ime foldera/fajla';
	$jezik['text214']     = 'Unesite drugo ime foldera';
	$jezik['text215']     = 'Promeni';
	$jezik['text216']     = 'Brisanje foldera';
	$jezik['text217']     = 'Da li ste sigurni da želite izbrisati folder';
	$jezik['text218']     = 'Da';
	$jezik['text219']     = 'Ne';
	$jezik['text220']     = 'Brisanje fajla';
	$jezik['text221']     = 'Da li ste sigurni da želite izbrisati fajl';
	$jezik['text222']     = 'Rcon komanda';
	$jezik['text223']     = 'Primeri';
	$jezik['text224']     = 'Pošalji';
	$jezik['text225']     = 'Reinstalacija servera';
	$jezik['text226']     = 'Da li ste sigurni da želite reinstalirati vaš server?<br />
				   Sve sadašnje podatke sa servera će biti izbrisane.';
	$jezik['text227']     = 'Promena FTP šifre';
	$jezik['text228']     = 'Da li ste sigurni da želite promeniti FTP šifru servera?';
	$jezik['text229']     = 'Novi tiket';
	$jezik['text230']     = 'Naslov tiketa';
	$jezik['text231']     = 'Naslov.';
	$jezik['text232']     = 'Izaberite server za koj je tiket.';
	$jezik['text233']     = 'Pitanje';
	$jezik['text234']     = 'Uplata';
	$jezik['text235']     = 'Podrška';
	$jezik['text236']     = 'Vrsta tiketa.';
	$jezik['text237']     = 'Hitno';
	$jezik['text238']     = 'Normalan';
	$jezik['text239']     = 'Nije hitno';
	$jezik['text240']     = 'Prioritet.';
	$jezik['text241']     = 'Otvori';
	$jezik['text242']     = 'Novi admin';
	$jezik['text243']     = 'Izaberite';
	$jezik['text244']     = 'Nick + password';
	$jezik['text245']     = 'Steam id';
	$jezik['text246']     = 'Vrsta admina.';
	$jezik['text247']     = 'Steam ID admina';
	$jezik['text248']     = 'Nick admina';
	$jezik['text249']     = 'Šifra admina';
	$jezik['text250']     = 'Head admin';
	$jezik['text251']     = 'Obican admin';
	$jezik['text252']     = 'Slot + imunited';
	$jezik['text253']     = 'Slot';
	$jezik['text254']     = 'Vrsta admina.';
	$jezik['text255']     = 'Komentar';
	$jezik['text256']     = 'Otvori';
	$jezik['text257']     = 'Bug report';
	$jezik['text258']     = 'Naslov prijave';
	$jezik['text259']     = 'Bug / Greška';
	$jezik['text260']     = 'Predlog';
	$jezik['text261']     = 'Izaberite vrstu.';
	$jezik['text262']     = '<b>Napomena:</b> Ako vam administrator odgovori na ovu prijavu sticice vam e-mail sa odgovorom.';
	$jezik['text263']     = 'Prijavi';
	$jezik['text264']     = 'Izaberite avatar';
	$jezik['text265']     = 'Maksimalna velicina';
	$jezik['text266']     = 'Dimenzije ( <z>Max preporucene</z> )';
	$jezik['text267']     = 'Dimenzije ( <z>Min preporucene</z> )';
	$jezik['text268']     = 'Formati';
	// footer.php - END -

	// func.razno.php - START -
	$jezik['text269']     = 'Upravo sada';
	$jezik['text270']     = 'pre ';
	$jezik['text271']     = 'Leglo';
	$jezik['text272']     = 'Ceka proveru';
	$jezik['text273']     = 'Nije leglo';
	$jezik['text274']     = 'Na cekanju';
	$jezik['text275']     = 'Otvoren';
	$jezik['text276']     = 'Pročitan';
	$jezik['text277']     = 'Prosleđen';
	$jezik['text278']     = 'Odgovoren';
	$jezik['text279']     = 'Zaključan';
	$jezik['text280']     = 'Zauzet';
	$jezik['text281']     = 'Aktivan';
	$jezik['text282']     = 'Suspendovan';
	$jezik['text283']     = 'Istekao';
	$jezik['text284']     = 'istekao pre';
	$jezik['text15123']   = 'ističe';
	$jezik['text285']     = 'dana';
	$jezik['text286']     = 'Produzi server';
	// func.razno.php - START -

	// func.server.php - START -
	$jezik['text287']     = 'Ne mogu se konektovati na masinu. Moguce je da je masina offline.';
	$jezik['text288']     = 'Podatci za konektovanje na masinu nisu tacni.';
	$jezik['text289']     = 'Nemate pristup ovom serveru.';
	$jezik['text290']     = 'SSH2 PHP extenzija nije instalirana.';
	$jezik['text291']     = 'Server mora biti stopiran!';
	$jezik['text292']     = 'Ne mogu se spojiti sa serverom.';
	$jezik['text293']     = 'Netačni podatci za prijavu';
	$jezik['text294']     = 'Server vam je suspendovan!';
	$jezik['text295']     = 'Server mora biti startovan';
	$jezik['text296']     = 'Mora proci 5 minuta da bi mogli opet da reinstalirate / promenite mod na serveru.';
	$jezik['text297']     = 'Ne mogu dobiti server username, javite administratorima o ovome.';
	$jezik['text298']     = 'Ne mogu dobiti mod putanju, javite administratorima o ovome.';
	$jezik['text299']     = 'Greška';
	$jezik['text300']     = 'Taj server ne postoji.';	
	// func.server.php - END -

	// gp.php - START -
	$jezik['text300']     = 'Nemate nijedan server da bi koristili game panel.';
	$jezik['text301']     = 'Korisnički panel';
	$jezik['text302']     = 'Vesti';
	$jezik['text303']     = 'Poslednje vesti u vezi sajta i game panela.';
	$jezik['text304']     = 'Informacije klijenta';
	$jezik['text305']     = 'Osnovne informacije vaseg profila.';
	$jezik['text306']     = 'Trenutno nema nijedno obavestenje.';
	$jezik['text307']     = 'Novo';
	$jezik['text308']     = 'Ime i prezime';
	$jezik['text309']     = 'Na računu';
	// gp.php - END -

	// gp-admini.php - START -
	$jezik['text310']     = 'Admini servera';
	$jezik['text311']     = 'Morate izabrati server!';
	$jezik['text312']     = 'Taj server ne postoji ili nije vas!';
	$jezik['text313']     = 'Ovo moze koristiti samo CS 1.6 serveri!';
	$jezik['text314']     = 'Steam ID / Nick admina';
	$jezik['text315']     = 'Sifra';
	$jezik['text316']     = 'Privilegije';
	$jezik['text317']     = 'Flagovi';
	$jezik['text318']     = 'Komentar';
	$jezik['text319']     = 'Novi admin';
	// gp-admini.php - END -

	// gp-boost.php - START -
	$jezik['text320']     = 'Boost server';
	$jezik['text321']     = 'Free Boost';
	$jezik['text322']     = 'Svaki server sa preko <z>26</z> slota moze boostovati svakih <z>2</z> dana svoj server.';
	$jezik['text323']     = 'Sve sto je potrebno je da kliknete na button "<z>Boost server</z>". Boost pokrece';
	$jezik['text324']     = 'Boost server';
	$jezik['text325']     = 'Nick boostera';
	$jezik['text326']     = 'Vreme boosta';
	$jezik['text327']     = 'Strana mora biti u brojevnom formatu.';
	$jezik['text328']     = 'Nevažeća stranica.';
	$jezik['text329']     = 'Trenutno ovaj server nije boostovan nijednom';
	// gp-boost.php - END -

	// gp-modovi.php - START -
	$jezik['text330']     = 'Modovi servera';
	$jezik['text331']     = 'Ime moda';
	$jezik['text332']     = 'Opis moda';
	$jezik['text333']     = 'Default mapa';
	$jezik['text334']     = 'Akcija';
	$jezik['text335']     = 'Trenutno ne postoji nijedan mod u bazi.';
	$jezik['text336']     = 'Instaliran';
	$jezik['text337']     = 'Instaliranje moda';
	$jezik['text338']     = 'Instaliraj';
	// gp-modovi.php - END -

	// gp-plugins.php - START -
	$jezik['text339']     = 'Plugini servera';
	$jezik['text340']     = 'Trenutno ne postoji nijedan plugin u bazi.';
	$jezik['text341']     = 'Opis plugina';
	$jezik['text342']     = 'Ime plugina';
	$jezik['text343']     = 'Brisanje plugina';
	$jezik['text344']     = 'Izbriši';
	$jezik['text345']     = 'Instaliranje plugina';
	// gp-plugins.php - END -

	// gp-podrska.php - START -
	$jezik['text346']     = 'Lista tiketa';
	$jezik['text347']     = 'Tiketi';
	$jezik['text348']     = 'Ovde se nalaze svi vaši pokrenuti tiketi poređani u grupama.';
	$jezik['text349']     = 'Izaberite neke od opcije desno da bi prikazali još tiketa.';
	$jezik['text350']     = 'Novi tiket';
	$jezik['text351']     = 'Zaključani tiketi';
	$jezik['text352']     = 'Svi tiketi';
	$jezik['text353']     = 'ID Tiketa';
	$jezik['text354']     = 'Naslov';
	$jezik['text355']     = 'Server';
	$jezik['text356']     = 'Datum';
	$jezik['text357']     = 'Poslednji odgovor';
	$jezik['text358']     = 'Broj poruka';
	$jezik['text359']     = 'Prioritet';
	$jezik['text360']     = 'Status';
	$jezik['text361']     = 'Akcija';
	$jezik['text362']     = 'Trenutno nemate nijedan zakljucan tiket.';
	$jezik['text363']     = 'Nema servera';
	$jezik['text364']     = 'Trenutno nemate nijedan tiket.';
	$jezik['text365']     = 'Ovaj tiket je vec zakljucan';
	$jezik['text366']     = 'Zakljucaj tiket';
	$jezik['text367']     = 'Trenutno nemate nijedan otvoren tiket.';
	// gp-podrska.php - END -

	// gp-server.php - START -
	$jezik['text368']     = 'Pregled servera';
	$jezik['text369']     = 'UPOZORENJE!';
	$jezik['text370']     = 'Server ce vam biti suspendovan u';
	$jezik['text371']     = 'ako ne izmirite dugove!';
	$jezik['text372']     = 'RCON!';
	$jezik['text373']     = 'Postavite rcon password na serveru ako želite koristiti rcon preko panela!';
	$jezik['text374']     = 'Informacije servera';
	$jezik['text375']     = 'Ime servera';
	$jezik['text376']     = 'Default mapa';
	$jezik['text377']     = 'Važi do';
	$jezik['text378']     = 'Igra';
	$jezik['text379']     = 'Mod';
	$jezik['text380']     = 'Konzola log';
	$jezik['text381']     = 'Pogledaj';
	$jezik['text382']     = 'Slotovi';
	$jezik['text383']     = 'IP adresa';
	$jezik['text384']     = 'Status';
	$jezik['text385']     = 'Grafik servera';
	$jezik['text386']     = 'Slika';
	$jezik['text387']     = 'FTP podatke';
	$jezik['text388']     = 'Username';
	$jezik['text389']     = 'Password';
	$jezik['text390']     = 'Skriven';
	$jezik['text391']     = 'Prikaži šifru';
	$jezik['text392']     = 'Promeni šifru';
	$jezik['text393']     = 'Port';
	$jezik['text394']     = 'Server status';
	$jezik['text395']     = 'Online';
	$jezik['text396']     = 'Ime servera';
	$jezik['text397']     = 'Mapa';
	$jezik['text398']     = 'Igrači';
	$jezik['text399']     = 'Prečice';
	$jezik['text400']     = 'Pokaži tabelu igrača';	
	$jezik['text401']     = 'Trenutno nema online igraca!';	
	$jezik['text402']     = 'Zadnji update';	
	// gp-server.php - END -

	// gp-serveri.php - START -
	$jezik['text403']     = 'Lista servera';
	$jezik['text404']     = 'Lista vaših servera';
	$jezik['text405']     = 'Ovde se nalazi lista svih vaših zakupljenih servera.';
	$jezik['text406']     = 'Kliknite na ime servera za detaljnu kontrolu.';
	$jezik['text407']     = 'Ime servera';
	$jezik['text408']     = 'Vazi do';
	$jezik['text409']     = 'Cena';
	$jezik['text410']     = 'IP adresa';
	$jezik['text411']     = 'Igra';
	$jezik['text412']     = 'Slotovi';
	$jezik['text413']     = 'Status';
	$jezik['text414']     = 'Nemate nijedan zakupljen server.';
	$jezik['text415']     = 'Besplatan';
	// gp-serveri.php - END -

	// gp-tiket - START -
	$jezik['text416']     = 'Pregled tiketa';
	$jezik['text417']     = 'Morate izabrati tiket';
	$jezik['text418']     = 'Ovaj tiket nije vas!';
	$jezik['text419']     = 'Tiket';
	$jezik['text420']     = 'Zatražite pomoc radnika.';
	$jezik['text421']     = 'Nepostovanje pravila = ban.';
	$jezik['text422']     = 'Tiket info';
	$jezik['text423']     = 'Naslov';
	$jezik['text424']     = 'Status tiketa';
	$jezik['text425']     = 'Datum otvaranja';
	$jezik['text426']     = 'Server info';
	$jezik['text427']     = 'Ime';
	$jezik['text428']     = 'Ip';
	$jezik['text429']     = 'Status';
	$jezik['text430']     = 'Igrači';
	$jezik['text431']     = 'Otključaj tiket';
	$jezik['text432']     = 'Zaključaj tiket';
	$jezik['text433']     = 'Server';
	$jezik['text434']     = 'Reputacija';
	$jezik['text435']     = 'Klijent';
	$jezik['text436']     = 'Vaš tiket je na <z>'.$brtiket.'</z> poziciji u listi čekanja.';
	$jezik['text437']     = 'Neko od <z>radnika</z> je pročitao Vaš tiket što znači da je tiket u <z>realizaciji</z>.';
	$jezik['text438']     = 'Ovaj tiket je <z>zakljucan</z>.';
	$jezik['text439']     = 'Antispam! Vreme izmedju postavljanje sledeceg odgovora je 5 minuta!';	
	$jezik['text440']     = 'Tiket je zakljucan';
	$jezik['text441']     = 'Napisi komentar';
	$jezik['text442']     = 'Objavi';
	$jezik['text443']     = 'Trenutno nema nijedan odgovor';
	// gp-tiket.php - END -

	// gp-webftp.php - START -
	$jezik['text444']     = 'Server WebFTP';
	$jezik['text445']     = 'link fajla';
	$jezik['text446']     = 'Pogrešne FTP podatke!';
	$jezik['text447']     = 'WebFTP';
	$jezik['text448']     = 'Pristup vašim fajlovima bez odlaska na FTP.';
	$jezik['text449']     = 'Menjajte fajlove, brišite i dodajte nove.';
	$jezik['text450']     = 'Novi folder';
	$jezik['text451']     = 'Uploadovanje fajla';
	$jezik['text452']     = 'Upload';
	$jezik['text453']     = 'Ime fajla/foldera';
	$jezik['text454']     = 'Veličina';
	$jezik['text455']     = 'User';
	$jezik['text456']     = 'Grupa';
	$jezik['text457']     = 'Permisije';
	$jezik['text458']     = 'Modifikovan';
	$jezik['text459']     = 'Akcija';
	$jezik['text460']     = 'Sačuvaj';
	// gp-webftp.php - END -

	// naruci.php
	$jezik['text461']     = 'Naruči server';
	$jezik['text462']     = 'Mora biti definisano kao broj.';
	$jezik['text463']     = 'Igra sa tim ID-om nije u ponudi.';
	$jezik['text464']     = 'Lokacija sa tim ID-om nije u ponudi.';
	$jezik['text465']     = 'Narudžbina';
	$jezik['text466']     = 'Narudžbina novog servera.';
	$jezik['text467']     = 'Informacije klijenta';
	$jezik['text468']     = 'Osnovne informacije vaseg profila.';
	$jezik['text469']     = 'izaberite';
	$jezik['text470']     = 'Izaberite igru koju želite da zakupite.';
	$jezik['text471']     = 'Dalje';
	$jezik['text472']     = 'Već imate naručen server, ukoliko želite da platite i podignete taj server idite na';
	$jezik['text473']     = 'Ukoliko želite da naručite još jedan server idite na';
	$jezik['text474']     = 'Zahtev za dizanje';
	$jezik['text475']     = 'Naruči server';
	$jezik['text476']     = 'popunite';
	$jezik['text477']     = 'Popunite podatke koje su neophodne za kupovinu vašeg servera.';
	$jezik['text478']     = 'Igra.';
	$jezik['text479']     = 'Izaberi broj slotova';
	$jezik['text480']     = 'slota';
	$jezik['text481']     = 'Izaberite broj slotova.';
	$jezik['text482']     = 'Banka/Posta';
	$jezik['text483']     = 'Način uplate.';
	$jezik['text484']     = 'Mesec';
	$jezik['text485']     = 'Meseca ( 5% popusta )';
	$jezik['text486']     = 'Meseca ( 10% popusta )';
	$jezik['text487']     = 'Meseci ( 15% popusta )';
	$jezik['text488']     = 'Meseci ( 20% popusta )';
	$jezik['text489']     = 'Placate unapred.';
	$jezik['text490']     = 'Srbija';
	$jezik['text491']     = 'Nemačka';
	$jezik['textBugarska'] = 'Bugarska';
	$jezik['text492']     = 'Lokacija vašeg servera.';
	$jezik['text493']     = 'Odaberite broj slotova';
	$jezik['text494']     = 'Cena servera.';
	$jezik['text495']     = 'Naruči server';
	$jezik['text496']     = 'Ime i prezime';
	$jezik['text497']     = 'Na računu';
	// naruci.php - END -

	// naruci-instaliraj.php - START -
	$jezik['text498']     = 'Instaliraj server';
	$jezik['text499']     = 'Greška serverid nije upisan.';
	$jezik['text500']     = 'Greška klijentid nije upisan.';
	$jezik['text501']     = 'Masina je trenutno offline ili je puna.';
	$jezik['text502']     = 'Ovaj server niste vi poručili ili nije uplacen.';
	$jezik['text503']     = 'Instaliranje servera';
	$jezik['text504']     = 'Pratite svaki korak i upišite sve što treba pravilno da bi instalirali server!';
	$jezik['text505']     = 'Izaberite ip po želji.';
	$jezik['text506']     = 'Trenutno nema slobodnih masina';
	$jezik['text507']     = 'Unesite informacije po zelji.';
	$jezik['text508']     = 'Igra';
	$jezik['text509']     = 'Slotovi';
	$jezik['text510']     = 'Port';
	$jezik['text511']     = 'Mesec/i';
	$jezik['text512']     = 'Ime servera.';
	$jezik['text513']     = 'Mod.';
	$jezik['text514']     = 'Nick head admina.';
	$jezik['text515']     = 'Sifra head admina.';
	$jezik['text516']     = 'Instaliraj server';
	// naruci-instaliraj.php - END -

	// naruci-zahtev.php - START -
	$jezik['text517']     = 'Zahtev za dizanje';
	$jezik['text518']     = 'Porudžbine';
	$jezik['text519']     = 'Ovde se nalaze sve vase neisplaćene porudžbine.';
	$jezik['text520']     = 'Izaberite opciju desno za kontrolisanje porudžbine.';
	$jezik['text521']     = 'ID Porudžbine';
	$jezik['text522']     = 'Igra';
	$jezik['text523']     = 'Lokacija';
	$jezik['text524']     = 'Slotovi';
	$jezik['text525']     = 'Meseci';
	$jezik['text526']     = 'Cena';
	$jezik['text527']     = 'Status';
	$jezik['text528']     = 'Akcija';
	$jezik['text529']     = 'Trenutno nemate nijedan poručen server.';
	$jezik['text530']     = 'Uplati server';
	$jezik['text531']     = 'Otkaži narudžbinu';
	$jezik['text532']     = 'Instaliraj server';
	$jezik['text533']     = 'Povrati novac';
	// naruci-zahtev.php - END -

	// ucp.php - START -
	$jezik['text534']     = 'Korisnički panel';
	$jezik['text535']     = 'Glavne informacije';
	$jezik['text536']     = 'Korisničko ime';
	$jezik['text537']     = 'Ime i prezime';
	$jezik['text538']     = 'E-mail';
	$jezik['text539']     = 'Registrovan';
	$jezik['text540']     = 'Zadnji login';
	$jezik['text541']     = 'Zadnja ip adresa';
	$jezik['text542']     = 'Status';
	$jezik['text543']     = 'Novac';
	$jezik['text544']     = 'POKRENUTIH TIKETA';
	$jezik['text545']     = 'ODGOVORI U TIKETIMA';
	$jezik['text546']     = 'BROJ SERVERA';
	$jezik['text547']     = 'CHAT PORUKE';
	$jezik['text548']     = '<b>Napomena:</b> Ovaj sistem je još u izgradi i trenutno ničemu ne služi.';
	$jezik['text549']     = 'Objavi';
	$jezik['text550']     = 'Prikazano 15 komentara';
	$jezik['text551']     = 'Prikazi sve';
	$jezik['text552']     = 'Napisi komentar';
	// ucp.php - END -

	// ucp-billing.php - START -
	$jezik['text553']     = 'Ovde možete dodavati vaše uplate i tako povećavati svoj novac kod nas.';
	$jezik['text554']     = 'ID';
	$jezik['text555']     = 'Opcije';
	$jezik['text556']     = 'Iznos';
	$jezik['text557']     = 'Datum';
	$jezik['text558']     = 'Status';
	$jezik['text559']     = 'Trenutno nemate nijednu uplatu';
	$jezik['text560']     = 'Otvori';
	$jezik['text561']     = 'Preuzmi';
	$jezik['text562']     = 'Dodaj uplatu';
	// ucp-billing.php - END -

	// ucp-billingadd.php - START -
	$jezik['text563']     = 'Billing ADD';
	$jezik['text564']     = 'Izaberite';
	$jezik['text565']     = 'Izaberite način plaćanja.';
	$jezik['text566']     = 'Banka / Posta';
	$jezik['text567']     = 'PayPal';
	$jezik['sms']         = 'SMS';
	$jezik['psc']         = 'PaySafeCard';
	$jezik['text568']     = 'Ove podatke popunjavate na osnovu uplatnice. Kako su upisane na uplatnicu tako upisite i ovde.';
	$jezik['text569']     = 'Uplatilac.';
	$jezik['text570']     = 'Iznos uplate.';
	$jezik['text571']     = 'Broj racuna ( <z>bez crtica, samo brojevi )</z>.';
	$jezik['text572']     = 'Datum.';
	$jezik['text573']     = 'Izaberite drzavu u kojoj je uplaceno.';
	$jezik['text574']     = 'Slike uplatnica ( Preporucen upload je na <a href="https://prnt.sc" target="_blank">prnt.sc</a> ).';
	$jezik['text575']     = 'Informacije o uplati';
	$jezik['text576']     = 'Primer';
	define('_GP_BILLINGADD_PAYPAL_TITLE','PayPal placanje');
	define('_GP_BILLINGADD_PAYPAL_INFO','Upisite iznos novca koji zelite platiti');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT','Iznos za placanje');
	
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_MIN','Minimalni iznos je 1.00 \u20AC');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_MAX','Maksimalni iznos je 10,000.00 \u20AC');
	define('_GP_BILLINGADD_PAYPAL_AMOUNT_ER1','Unesite vazeci iznos za platiti');
	
	// ucp-billingadd.php - END -

	// ucp-billingadd.php - START -
	$jezik['text577']     = 'Klijent logovi';
	$jezik['text577sa']     = 'SMS Billing ADD';
	$jezik['text578']     = 'Logovi';
	$jezik['text578s']     = 'SMS Logovi';
	$jezik['text578sa']     = 'SMS Billing';
	$jezik['text579']     = 'Ovde se nalaze logovi sa vaseg naloga.';
	$jezik['text579s']     = 'Ovde se nalaze SMS logovi sa vaseg naloga.';
	$jezik['text579sa']     = 'Slanjem sledecih poruka mozete dodati novac na vas nalog.';
	$jezik['text580']     = 'Ovi logovi su dostupni i adminima zbog sigurnosti i provere.';
	$jezik['text580sa']     = 'Podrška: support@gb-hoster.me Mobilna plaćanja fortumo.com';
	$jezik['text581']     = 'ID Loga';
	$jezik['text582']     = 'Log';
	$jezik['text583']     = 'Ip';
	$jezik['text584']     = 'Vreme';
	$jezik['text585']     = 'Trenutno nemate nijedan log.';
	// ucp-billingadd.php - END -

	// ucp-podesavanja.php - START -
	$jezik['text586']     = 'Podešavanja profila';
	$jezik['text587']     = 'promeni avatar';
	$jezik['text588']     = 'Korisničko ime.';
	$jezik['text589']     = 'E-mail';
	$jezik['text590']     = 'Ime i prezime.';
	$jezik['text591']     = 'Država.';
	$jezik['text592']     = 'Salji e-mail';
	$jezik['text593']     = 'Ne šalji';
	$jezik['text594']     = 'Obavesti me pri restart/stop/start servera.';
	$jezik['text595']     = 'Preskocite';
	$jezik['text596']     = 'Šifra (<z>Ostavite prazno za istu sifru</z>).';
	$jezik['text597']     = 'Potvrda (<z>Ostavite prazno za istu sifru</z>).';
	$jezik['text598']     = 'Proveravamo da li ste mašina ili čovek.';
	$jezik['text599']     = 'Sigurnosno pitanje';	
	$jezik['text600']     = 'Morate biti ulogovani.';	
	$jezik['text601']     = 'Sesije su istekle ili ste promenili podatke svog profila. Ulogujte se ponovo.';	
	$jezik['text602']     = 'za';	
	$jezik['text603']     = 'istekao danas';	
	// ucp-podesavanja.php - END -

	$jezik['text604']	  = 'Rank';
	$jezik['text605']	  = 'Najbolji serveri';
	$jezik['text606']	  = 'Zaboravljena lozinka';
	$jezik['text607']	  = 'Uspešno ste promenili lozinku, ulogujte se.';
	$jezik['text608']	  = 'Uspešno ste poslali zahtev za promenu lozinke, proverite email.';
	$jezik['text609']	  = 'Ne postoji takav username.';
	$jezik['text610']	  = 'Nedavno ste zatražili promenu lozinke, vaša lozinka je resetovana i da bi to potvrdili morate da kliknete na link ispod';
	$jezik['text611']	  = 'Link za potvrdu';
	$jezik['text612']	  = 'Vaša nova lozinka';
	
	$jezik['informacija'] = 'Informacija';
	$jezik['info_poruka'] = 'Par sekundi nakon slanja SMS-a stici ce vam povratna poruka u kojoj ce pisati da li je placanje uspjelo ili nije, ako je placanje uspjelo iznos ce biti dodat na vas GB Hoster racun!';
	$jezik['info_poruka2'] = 'Vodite računa pri slanju SMS-a, mi nismo odgovorni ako pogrešno ukucate poruku ili broj!';
?>