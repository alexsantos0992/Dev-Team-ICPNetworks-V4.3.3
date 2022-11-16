<?php

// error_reporting(E_ALL); ini_set('display_errors', 1); // uncomment this line for debugging

/**
* Project: PHPWavUtils: Classes for creating, reading, and manipulating WAV files in PHP<br />
* File: WavFile.php<br />
*
* Copyright (c) 2012 - 2014, Drew Phillips
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without modification,
* are permitted provided that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice,
* this list of conditions and the following disclaimer.
* - Redistributions in binary form must reproduce the above copyright notice,
* this list of conditions and the following disclaimer in the documentation
* and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
* ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
* LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
* CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
* SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
* CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
* ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* Any modifications to the library should be indicated clearly in the source code
* to inform users that the changes are not a part of the original software.<br /><br />
*
* @copyright 2012 Drew Phillips
* @author Drew Phillips <drew@drew-phillips.com>
* @author Paul Voegler <http://www.voegler.eu/>
* @version 1.1 (Feb 2014)
* @package PHPWavUtils
* @license BSD License
*
* Changelog:
*
*   1.1 (02/8/2014)
*     - Add method setIgnoreChunkSizes() to allow reading of wav data with bogus chunk sizes set.
*       This allows streamed wav data to be processed where the chunk sizes were not known when
*       writing the header.  Instead calculates the chunk sizes automatically.
*     - Add simple volume filter to attenuate or amplify the audio signal.
*
*   1.0 (10/2/2012)
*     - Fix insertSilence() creating invalid block size
*
*   1.0 RC1 (4/20/2012)
*     - Initial release candidate
*     - Supports 8, 16, 24, 32 bit PCM, 32-bit IEEE FLOAT, Extensible Format
*     - Support for 18 channels of audio
*     - Ability to read an offset from a file to reduce memory footprint with large files
*     - Single-pass audio filter processing
*     - Highly accurate and efficient mix and normalization filters (http://www.voegler.eu/pub/audio/)
*     - Utility filters for degrading audio, and inserting silence
*
*   0.6 (4/12/2012)
*     - Support 8, 16, 24, 32 bit and PCM float (Paul Voegler)
*     - Add normalize filter, misc improvements and fixes (Paul Voegler)
*     - Normalize parameters to filter() to use filter constants as array indices
*     - Add option to mix filter to loop the target file if the source is longer
*
*   0.5 (4/3/2012)
*     - Fix binary pack routine (Paul Voegler)
*     - Add improved mixing function (Paul Voegler)
*
*/

class WavFile
{
    /*%******************************************************************************************%*/
    // Class constants

    /** @var int Filter flag for mixing two files */
    const FILTER_MIX       = 0x01;

    /** @var int Filter flag for normalizing audio data */
    const FILTER_NORMALIZE = 0x02;

    /** @var int Filter flag for degrading audio data */
    const FILTER_DEGRADE   = 0x04;

    /** @var int Filter flag for amplifying or attenuating audio data. */
    const FILTER_VOLUME    = 0x08;

    /** @var int Maximum number of channels */
    const MAX_CHANNEL = 18;

    /** @var int Maximum sample rate */
    const MAX_SAMPLERATE = 192000;

    /** Channel Locations for ChannelMask */
    const SPEAKER_DEFAULT               = 0x000000;
    const SPEAKER_FRONT_LEFT            = 0x000001;
    const SPEAKER_FRONT_RIGHT           = 0x000002;
    const SPEAKER_FRONT_CENTER          = 0x000004;
    const SPEAKER_LOW_FREQUENCY         = 0x000008;
    const SPEAKER_BACK_LEFT             = 0x000010;
    const SPEAKER_BACK_RIGHT            = 0x000020;
    const SPEAKER_FRONT_LEFT_OF_CENTER  = 0x000040;
    const SPEAKER_FRONT_RIGHT_OF_CENTER = 0x000080;
    const SPEAKER_BACK_CENTER           = 0x000100;
    const SPEAKER_SIDE_LEFT             = 0x000200;
    const SPEAKER_SIDE_RIGHT            = 0x000400;
    const SPEAKER_TOP_CENTER            = 0x000800;
    const SPEAKER_TOP_FRONT_LEFT        = 0x001000;
    const SPEAKER_TOP_FRONT_CENTER      = 0x002000;
    const SPEAKER_TOP_FRONT_RIGHT       = 0x004000;
    const SPEAKER_TOP_BACK_LEFT         = 0x008000;
    const SPEAKER_TOP_BACK_CENTER       = 0x010000;
    const SPEAKER_TOP_BACK_RIGHT        = 0x020000;
    const SPEAKER_ALL                   = 0x03FFFF;

    /** @var int PCM Audio Format */
    const WAVE_FORMAT_PCM           = 0x0001;

    /** @var int IEEE FLOAT Audio Format */
    const WAVE_FORMAT_IEEE_FLOAT    = 0x0003;

    /** @var int EXTENSIBLE Audio Format - actual audio format defined by SubFormat */
    const WAVE_FORMAT_EXTENSIBLE    = 0xFFFE;

    /** @var string PCM Audio Format SubType - LE hex representation of GUID {00000001-0000-0010-8000-00AA00389B71} */
    const WAVE_SUBFORMAT_PCM        = "0100000000001000800000aa00389b71";

    /** @var string IEEE FLOAT Audio Format SubType - LE hex representation of GUID {00000003-0000-0010-8000-00AA00389B71} */
    const WAVE_SUBFORMAT_IEEE_FLOAT = "0300000000001000800000aa00389b71";


    /*%******************************************************************************************%*/
    // Properties

    /** @var array Log base modifier lookup table for a given threshold (in 0.05 steps) used by normalizeSample.
     * Adjusts the slope (1st derivative) of the log function at the threshold to 1 for a smooth transition
     * from linear to logarithmic amplitude output. */
    protected static $LOOKUP_LOGBASE = array(
        2.513, 2.667, 2.841, 3.038, 3.262,
        3.520, 3.819, 4.171, 4.589, 5.093,
        5.711, 6.487, 7.483, 8.806, 10.634,
        13.302, 17.510, 24.970, 41.155, 96.088
    );

    /** @var int The actual physical file size */
    protected $_actualSize;

    /** @var int The size of the file in RIFF header */
    protected $_chunkSize;

    /** @var int The size of the "fmt " chunk */
    protected $_fmtChunkSize;

    /** @var int The size of the extended "fmt " data */
    protected $_fmtExtendedSize;

    /** @var int The size of the "fact" chunk */
    protected $_factChunkSize;

    /** @var int Size of the data chunk */
    protected $_dataSize;

    /** @var int Size of the data chunk in the opened wav file */
    protected $_dataSize_fp;

    /** @var int Does _dataSize really reflect strlen($_samples)? Case when a wav file is read with readData = false */
    protected $_dataSize_valid;

    /** @var int Starting offset of data chunk */
    protected $_dataOffset;

    /** @var int The audio format - WavFile::WAVE_FORMAT_* */
    protected $_audioFormat;

    /** @var int The audio subformat - WavFile::WAVE_SUBFORMAT_* */
    protected $_audioSubFormat;

    /** @var int Number of channels in the audio file */
    protected $_numChannels;

    /** @var int The channel mask */
    protected $_channelMask;

    /** @var int Samples per second */
    protected $_sampleRate;

    /** @var int Number of bits per sample */
    protected $_bitsPerSample;

    /** @var int Number of valid bits per sample */
    protected $_validBitsPerSample;

    /** @var int NumChannels * BitsPerSample/8 */
    protected $_blockAlign;

    /** @var int Number of sample blocks */
    protected $_numBlocks;

    /** @var int Bytes per second */
    protected $_byteRate;

    /** @var bool Ignore chunk sizes when reading wav data (useful when reading data from a stream where chunk sizes contain dummy values) */
    proÉZ`÷è†KXKÏŠº@ÛO}°Z~K¯°ü8¨»7 ZğUÈƒŠu´!}%]E%àFHœuñ%x÷„œ})µƒù%X@Î–/q%<±mË¶¼ÔBwÔæ Ğo‹|\ Ç¢6}4ğ+C£ Ô<8Á²]`Gà?lŒÛÍøGœjMœ	”DŒ4¡Óà¾P„…³tI`‹ïM¤(u©¾¶@Y¨¤U74`a‡
2°kÆÎUpÚ
P„1;Ñ¢
R/8#X¼{L/øCxéNşô‰ü2j öRlWŒUüÅG´ãôˆHïRøhÍheV¿euÛGáX³•)è°Œvü(9j0 .k‡ø}DÄ
TŸÚÃ\ÁÎ§v@¼AÀ`w(Øø¹ô™9„ ¶²&X± xqÂÏ$x‹Eô(–H0X$¢é9øŒÉ‚j‹9…|4¶ªL)„xƒ·À„"!¹mª=@2E	’u?k76<4V%LÄcˆI³ìÏx	SjM4èT»ÿÿoû$.ˆ$$r{wôëzØVp¡@È˜	8H'4 c!SE´+Q'ìğ÷ x¼£eÔÒtfŒ¬ş¢ °bˆİùyø>lÁ ÁÊ.Ì
‰~µ-ôóf¸fƒã‰ø”H)ĞÁv$>uf¬”P#{{®f‰*.;áÙŒVŒT3/ŒğŒV‹£˜Ét/~Ï89£Ô”fM3ø3”&·P•ÊıîŒëc'¤8«@üPyi²¢C:Qíaƒ:(/cÕd)»…ø}*äùEøi9Ü
ğ¬+}øƒ/‹GÚGGëBìNC\Às,ğu&uŒ°$æ¶+ãë¬d0¾_ +¢ŒQá‚42,öl’ûa„R„ˆ^1¨ˆˆˆT#%PîıÊ
–Š°ˆ¸ÏXôê¹ ÷¹fK&Å«ª¦Q$—ğt&3ÀÃ;ÓÂ¨Ø<Øv|^¡cë,@È€èö³Š  ô¥»ãHgt‰Æ  .AğC0‚`h©X¤”{lw3ÊÍ‰j‹& ‹$Sg„/`@*ìe¹f+;İgQ¢ã. 8cúQ°õ‰	¬ëb‘0ü´>:{­Qòy\kÕbw~Æ"´‹¬b:ä‚YB²U)It„?OJDcĞŸDe Ÿ¶;Xu/t:à‡vÈd]4(iÜÔm'[,Hˆp²o+8H)Ø ˆ¬Àí‚A˜El¦Q‹ Ñø|›ÖBâu¯x%‡B‹Ú :¸B"p%#ÏÈÀù #WúÃ	“$¡°jR2b Å€àÁ[ûÆ[¤¨¦Zj ;mB‚ÏŒhì9 Œ¼z5g¢‰­½L4%µd±-ñp„ èê‡½$8RW-paÉ¬hé^‚OøÒ‹M¶„‹Uhê+€Ò7Q	áÁ"t,© ŒîN|È‡2H(@`-Ş±€8<ƒ½b°@„)úd!0“Oìâ&`öä	ğEZd¨R˜T<F	…ì÷ÁMİAkXĞ-o0ûF¬Nûƒ½k7 ÔoM 9›ÀªÛ+çzŸ^)	‹B{TìR‹ûğ´
J..ğqªVå£}Á ‡³,9kÀdAB»u8è*Ö€4a;Œ`ËRzæZq"hë}é	^º´Ò «şÿÿÔ„à€² |‘Á‡¹Rv2vÄ‚³=tUõm€ë¸ÇÉª	ƒOº*\FÂHWp? …È¨Œˆ‡Ä†Ø‹fV]şa‹<lŠŞ«÷…Ğ8O„¨àS ª°<``ıÜj îl­Gj³<Dmj‘8vÀ3ÉrúƒD2V©-4İ°ƒAŸ ªj Ä…X‹=h‰ô$ŠÅˆU’HdğÈÊ<ÆÅ°©|rI¼°€9qu/%ä$MğÎäÌ	àu?ï`ƒhğ€m rd…ÀW;Ø}')½=—)ÀY‘ÒJß×SQD¼EŸ-Ø˜…j2ù”ldKY÷ÚmH½upİ	Û‹0Œ‡Tˆvì±ÂR#j+ÖD5û’T@ìöZ•˜j,xH½ì€,ëzİunY9dä°{ˆˆˆ+ä”jÔ:9B¾ÔjAÈŸ³¦45Î)‹»Á"£!api0¢!qïàbaX8üøŞ‚ƒÕ†ùtz
;\.±@NhÜ{M„¬ìğ…D@³ 5—ÄÈ j 1°%_É
òEPÇD,€DöO„`ÔBâàÔ÷¤V°;r\Ÿ{!R›Ø	¿B8LBqÌ÷èÉ…DÀ-V«E"Ö£jñ¶P+jWo'ŸB.Wƒ¿™‹•Ôéˆ…bêìCb$“‚| Â ™ìèÂ à€§)‰YHz
	„!hë#ËNÆ…­±A¹ÄD@
[D2.‹8T –¿åF` Åwé
	â d‡éMIp·:UW$•Hâ’<°éÕH&lÃ„Wr- ç°Rë~*ì°ëTT„È&€‚ë)*®¡ÙpğM×5İVŸÍøì"Mv„<ØÃ !`	C	à	¨¸¤-$ L.%wtãBôÄ+r	¤œˆ®d+`µï„ğhD¸¡u#Z¨vä´å’¢ERYmB=´å¿¯0¼|¥,mØ$@G
+ı*Ş‹	Ñ/tM³PU)‹¥H€QW
ÄFµF°¥vwä ü’A[Eìy°äL²¿×jp"!ÍEĞì\`¿f·*[	§˜åfE?jX4[ ÔAÄû©ÚKğ ğVb x…dÏˆ#Á×9R{HªÂ7*¬_:ePÇ`€ã³€ãfU ¬`¾aGvœK„<ü÷LDÎ „ÄŒ‡=•íbˆg ÿÀ M$ë5j!_`“øjüDƒBˆ¦bX½] L°Êóè‡W œuG:°À¨tñ «!7|š
€7dk›|F',°ÒR¯ÄİÃ; O*(Oü|†pøE<dB`”¶3^À°	^—eĞ¤x—*ˆt°†± vT,æúf@¡Wªô.Î¿J‘l¾T&T H±hì;ÆN T]5ÜÛĞ¦XU'¿?Cr,‘?1¤”]1¤Ô]%DçÔkp5ÒCP`t1d!zEsxğk  âaÃ+RÄ
xD1a	q-0ø>a•Ra EVŒ)PÔ!J²^!ËDü!­J°uTr`#¬mTÈHtmT{Õ	xë%D°ÀdÄéàT;´À&¤°^ËIU°“ÿj Í€`UÜ~RE @0R}ıŞxæ â‰„‡1‹,¾1âê(¥DA€!Êâ|¸ñ^°	Èÿ	»ìŸØ
Q<m vöX5t3
&ÃŞc-4Æ„/6|zÄBÉ¬üN4p~Å
,…m"‹•p_D°®5‚U„Ğ
Y]Rğ‚QPD	¬s6‰´²R«ªRe>}Ïe?©D„0QtoFĞ®ØÃ0ØY×Ä;‚Q;‹T÷ìÀj"9ØkœŒ2Ï!Øï`Æ;:tp‡<Bµ Ö	|³üQ'DDÚ°€Ë9'o½S´Å7o_Ê:ÙA
ØüÖØ ‹Dv±€:€)(¹÷=©~¬Õv…\«xÀDŠ®Ùôøü¿À•Ö\-!?4½e%x€“Qj ®3Š³HRìÑÉ‚$%D,äŸ³<‹PôÕ|Á†A1P=Hpa}=!Z`öë"n 0n@Â¬l4 ğwÕ÷bš…9C‘X z5+š¤DôT’C@X¼å  ¨ä!’Õ¨Õ¨ÕB^!¨Õ¨Õd ä€.˜
iEi¹ ,i 0Ùmj#¤))4tÔX(9ıÅ ìAë,½À6œ¨djQ Uwô<ïŸøjü…  A0üQ~0!á`ôå@N’)ü9ä@ÌŒä*rr'
V„Å±€¥î	Š•X‘ˆGúd‡Äø´úŠ¸	m±hÈ¼Uèä|âÎ®œÆÌÊ¼Yñ]d„#°®ˆu˜{ŞdRüŒ}\QjL¤C˜è”ËˆDM±Š[4„1˜…4nLÏÛ“I°úF¿“q[	4ãşÅ!+ë   q ¬a@C"¡ŒWÄK0<%4mEV]k ëşŞ%ë‹Á°/‰+âğ+m;•‡:ıpŞ‹‡+5™¹'÷ùØ»=øÓ)I…üÓ"aIÈĞÀlFì‹¢Iÿ±
FşÁ ø
kRbÂEÆCx¸$l%xPFØˆ]ÇÓ•¯Å"üÂ®ÈEÂ;…ìğ!!Œ5ğPQI¨PĞ…°–ÖSñ“S	İL<^ñèÓ•ğoÉèÓ‹•èÓñ!#vàßääÀé!L‹}¯½SRäC½¤h…„Œ—‚!Ë§ @ùÙIú`-XèeuCH;eu@nCX‚‚}ßÜ¹QK©×Œh€…¨gŞ’'m_eê‰C8w|,v†Hó2ÈTF†vØØ0ş‹…ØvÆ„Ğ–Ô5Ô–„İƒH|grÉK`ı$ÖÔ°:á :¡ä%èÂBôòÁA5•€=Bèè„¶eØx³„ú:dÀ6âI:À’‚£æB´.æPğ„ä¤úœ|+P¨¿„äÁ;
¨+ÀÆ‚$+œ}+ß_Ã¬1Q‹•RÂ{±‹kU7&#»CŒ@ù 	àÅLamdä2¾,pùÌÌ†ƒä€RyË{-”Ôï½€ã¼0â( È`ù†/0âX€°¢“0â×6Ô‘ …_”ğ,Úõu©®˜Ôv<,˜Ô4áÂh€ z„áì$t˜ÔÎj OR:fŒiQ813„ù¸ Üt)˜ÔÎl`„HôRHó‚:]ÂÂ<j·;†yŒÍvõ²¼\ÆDûÈ$Œù0IûP‚ƒ€Œ…È…\r–²8ÄËË ¾ÏÄ²QnEÒ“ˆ	Ÿt^³Bğ^uy©ælğà‹dÓÀ•¹Á‹¸É»¸ÓÙ±Ş¼Ó‘Îj"!ñ$ºì°'¬| J Ü´‘ÄÅZÆôÄœÕHÛs¦.3	`Œ@BV [º[xŒnœÕVğ@îAø•â°G |•œÕz4Ç$Q\R”Ôä”ì[ †Q(<‹#Æ"]èÿI›Ø†L Õşæ #h/å´/„I'Ã(zÆ~‘j+ÇR‹.PàÂÙ" /HìñäA<Œ¥hì3ÒÿÚ88½ïş#c·3ÒÓ,C¨ä“(xv;¤ä>AØAÉ :úA3<÷
á8ƒ88Ş˜ÀAÆk	6™Í¬ª¹÷R—VÕhd¨¾öbnQß%_°6P<Áê9j¤I!TcØ±9×uV[9ùu0Y²;Ÿb9u! É£_9ôŒ">±ˆWAŒI=2„B••°Ó¹	é/v¨+…¨Bq¹¬°¤ÓR°ˆ’æQ†1‹*Pû…Gî¹ÉAWBR™ÉÁ‚!·k Ó$‚Ax ÓÙpXc#–œ&”È„ŒŒãœ”˜†5„\œ&,©%İ+Àú4xÛ‚F];ºş8ñìŒº•)TĞ7„º…\;çt{‹BHc¼ı8ÎÅdÎˆ€\È„ı+…€ê„ˆ 7„|¢…BüËñÿÿƒb„3‡2 Ÿ3ıÕ $•xœ‹…¯Åbõ›
ˆÒU„;Æ›ßVcÂpŞtót›ÖrD–( x£ «…PÒöØOÉ‹…¸rréÁ°½rèİ™(hbÄ<`r°`¬ä”"şµšŞAuB@Àa¡4ÂšQ`Â"Êlıø[rl‹…lıÆ„«dah5hÑƒa@ÁÃB µ`Óÿkï ¹iÒ‹JL@‹ùD_„DÆ…øtÂC2a\Ó´°/Æ%
XpQs}ñ¹\ŒQĞ¤¾C–•[RÔ„T'øöN•{TÓiÉ{•T„BaÿD
`‹øR±%C <ğşkP²2Pğş{LÉÈ0âLLÂèœ`ä„%ƒpOHåÈ•0ÂDbDåH©‡%CR¶äP!ğş¦d"@@””q#<c®HÈ<k8aì’A4j4k'+8tl0Æ„ø   Á0MAÆ”,0Á,uDÆ”{,Á‚ˆ©mubY@àÉaƒŒ® (Š	L(JrØŠ0Ã­$0F$S$ŸŠÅ%ñ Óÿp¬ñ+ÇFÆbØsÀ2”ğØÂº¨R@T`¼%DŒ—ÔsD°dUoÙ°äŸ÷+¤bRæKI‡2¬¤laÔöÑDíe:™;5Û:«”ŞaHô…{P,Ã‰z	ğ@Ä`ÄúÑ	ÚŞÄêx„"!® Yä® ‹tÉÉÈ<•Eo	tŒ1`qßaÈAXß<=ö#Œè°™}Şj.Á˜¤¢‡QO€Á¼Š½eè±Kà@¤C š^FÄ~R‹,St1¼.ù,˜ü–øB p€Ô|Š qu²ÿøÒÒx½‘1»¶@Æ8ô@%ˆŒ¿rôõPhì%'	ŒÚND¦Lj	Áˆ“|ŞÓ´v²d“Ç‰ „|"`)@¡x@XhÈK,0â0â{VA„€‹w-v	4„:€¨!|¯ŸNˆµÊÚAzğj Q~õ"”às—PäğÒå³„@Oy(–° ÏıÁ“ÃBÇè@+[FÆÇäØa¼’ètI—àHÅsI5KÒÒ@Æ”Eà¥-d6Y?Ì-Dj÷p‡ƒj¢„Uª[B•Ş¤|IE|?…ğş|„F¯u2j Å¤èu²ë
Dê°ĞÈ3^_ívúuøöÉ‰øö¢øö±a‘\$¦oD‹%_ÀC`…uØÒ`ìp$+Ô“PŞßÂ¦+„
“³’K"š“ÙöØ*ºöü4-ìDj ÷Å•ty r8ĞÒĞÒ•ÿ’‹…ĞÒtpì28ÈÒ”Ì5ÌÒ8 a”8?¸¨o"\ #¤“¬OËÆ E}¨A&@43Ä€ğÄ‹…Ä3ÂØ€¼À5À3Qp4=\b ä?”?Ñ'p°€‡!?t=¬Ë*®?½¸ñ`g¸=?#’d0„´–´‹…´È –¬°!Œ5°–06¬äe=›3<¿n
,àgP=XÀ€ı?0è„: =€ûD?èF=^ö+°=>øè:>ì À=>àÈ)S>Ô>Èñ9T>¨>œ&#—ù¨¨PòÈ=ô u{Ã‰Ê9•\$àŒtÉ¤A¤€Œ³fd Aª„fä’³T²„ e 	d fXßD'©h‡àkëu€e¬ ^¬:p¾Xà0£âò>P–‹cäd Ÿ8P>@0¤èƒpÔ‘Ñ“,)l•hÔûÀ¾I›P(<‹•B4Ö{,Àí²†@ßtÔË^lHxåFj¤Û†x”St0`zFv½/xÔt4b K>!Œod¦6Ôšc Á3•d:pFeüÛzÈÈÈ>ÀìÀìúG Z±J
-à@´dQpÌ£HJQÀ7ûa<Áè:ka CGd
	ìx9•ÜS\°dÀ9Ş6 cìû9…¨5¥—°ûĞRtUy=è«ÙÒd|ƒU.$)Ìß`	ÁNë‚
Æ¦u]j@ˆº²ÜAùß7YõœÒâQ}°x,„ ztÇ…ÀP@èPÀ·ä˜ÒÚ=”ä$W	PV=x“@À!xãÊt$,¾*\F?dä²¾#Lô””x/ä`u#,8‹•h¾£§LºP¨Æ¸˜úÙ´åí`–}/$	u<$-¤ˆıÎ<„á±`àuƒÔ¦¸Ác3ñdì’Ÿ0â’ïQ¹u-,`GèŒÀŒÒ$¾l,tş ëa5 5‰KˆÍˆ]öÀ†Eb<ô,„Ÿ,Œ„b‹•ìşb	j	•–ó9u#q òôåH¹"’ä*9<Ìd*©¦,€R€‹…€c2 x|5|euR,±­„ı^h	Ô] (@h^/É‘GtÒtÒ`S	ô‹•tÒ^lãp22pp("!zÉ<À‹ ĞÄm‡‡²€İ$Dm ‡hÒÜŞ#hÒ
øhÒ6•‡`ædı!#d¡ëuŒ¿€±@\G‹…\GÆl T|X5X	Â|ıAá(ÎÂ?q  •@Œ†#G‚®†Œb= 1YES‹Ü)r°kX6²o=X0ƒjFj\(bKâ¨ FDÍÖ½@aâë%¿‰(„ŒR6D°	ita@Ò¼'1³ qã£ä *èHP'œ†¢í£àLG	š ¿Tà@Ú’ˆÁnJ P=Fò "Un‹\Â^|ğä\ÿ# -‰Yì,Ø½– QIº®$®ne$¨äµnxË^rèU k®@C€Ónà{Võ¿ ´FÔS°YE/Eœf~0¬VP²ˆ4 Íä<äf 
ÂëW¥¼cÅ)ûäQjUœ¶'ØdSL©½ğÙ|täsğİ’v"©(dx“Ü¬şuğ+Í©"^ĞÈè{^ß†3ª{X_ë¼ûŒÈèfÀÀ…É3b¿Ã>´èGT3
Ç3H*zœ°¹PF°ë
¬{ö§°¼uLëg`cƒÑu^¤›]^ä³`ÏH=ÿ˜èÊ;°';D°èµ‹¼_k>Å‰lz-iúùEø™ìatMööUôò²ˆIîìè3áwID$‹óü¯AïQ<æút¤LjId¬F€«5 Ñ‰¼†U°Pª _ŒAq¬°‘ ´Ûˆ¤"=G|ûlVQ¬Ì#Qã\óğ¨\£k 1Ï+aŒHüR+Œkçˆ+4÷mçaüP+Ÿ@ ;}c¬[( €Ÿ8(ì982}/ ³‘``(	v¢õ¬°¿àØ»W3£˜ª¥# ş©Î8Ø	¹IÜpè*ä)?—ì;¶‰,ƒ½ÿ@6=Ã;À({	Äñ5b=j»´àF]ôşùì`„Î9àCüğ,ÊGB½»ÂÊiQÑ±‹•Agü_?nÿ¦d°-#Ùıüˆ˜±Øó«<Â3j‡cÃÆHÿC<$×Ex	È•8ÿgºX‹`H¤ÿ.Ql6Ma#d°ıÿèf]!üÎ´ë-%@@ÿ‚`„¢u‹Šfà'×ı¶÷‹BDmH`ˆÛBßE HˆY%õÕ(%!Ïÿ²ÖqW[ }6çõO&¨zÚHTèSYŠàŸ$Uğ„ğAE@yjÈ3QĞP8»sŞ¤: 2<Ö’ÀñÅ Ç¤c'5BQÌÿUvjàdÍ1R	€`¬Ád”BĞÃe ß9‰YAXÈğ˜AÜZE%ºÅ:P ™"«œªˆ`0ğªAp«ìaá`áH¤j-ôëHŒxˆÒßbu¨@Wq*Ø„@m ˜ âÍ‚Kğ÷Òœ;Am^P$Ù­"2Ú„
ë"/.ÜjÿkxhNXÜvC/„äÄ˜ì‰eèd$¢k œv0Dp$6«2°Á Æ#ä+CÔöTQÄuÄSÛL}=MZ œ"ßØXeœ?‹'_ `ãQ<V¼ê8PE6!g,˜˜lĞÓw^¼<Qú„ÓöBGu±¼m°‹M‘Ín® ¶JĞ|OHx!İVØ†Ğ°4uP”2”ÈSVÛîUØØ\‹.Æ63ØèÈå„tÈ}ÈjrG{"âÈëÃ¬Ğn´İ6S–EB 5Ì Ñ-z¸)Àú?öÁÀˆÀ!À;BÛÃØƒ£ÍÌn¤KrÃC5ØiM¤Qr´E&¤ŒŒ[¨ÛØ!5Ö¬™¬°¬¬fŠr·;­s-A$Ò,›kz+Ñ0)¤h{¬‹kØÚØëÂ,¨º]rIJ&3ë&Ìƒ@kİV3&Ìk©Ü„	é†EˆˆÆon´$2ÔÜÔí¥‡±A´HMÜ‹eÙÚ‹
Ü¥‚uœÜ+àNÎÖ5! 	­ñkˆä‹]ƒ‚ƒHŸ µE %`u”ì–—ğ€XX»OuY“µÜøôô©S ÏuYËuIxHum5øğQI œDÜ´úN@;ê¸ä0Â"¤ôØ­•õEğ_zP«‚MJ˜6æëüƒšŠ}ğ;sYl@çq<ØøJ+HFÁEôÑNF<€<üQş`U0ËjÉ 8 OjË@1´
ÛYì!&…@iAÀÆÆ°g{Ô 8*x%•àvòçÿ :ˆJ,ª{ö»tö‰;•sF*ƒxB›-9p<`‹z½öv+BB%T×£ÆOrö,F=6ƒyNhB8`"‹•ÒJ6"öµØ'!P³€x"zô`Æ}D2è:@!X Á.àˆè~UäX1Ç¶èëÁ¶ÒÖÛÔè
EØ;±mît0ìZì~‹DÚ»uK² ‹§l°º…dId»Qj1ÿíÒ±b¹ªü‰Oë¬Ô\K×éf`kZ{;ÜRØ v^°Ú»{@,^¹lº`%¨ä^€T^“wÈ`µXSÁ@éÂz	¶@`³El! @g‡Èù¶Ã¶60ĞrĞĞT´¹7Ìt0Ô[fîûtĞÔ‰ 2Ø•\t\°0‚ö1ÄçÄQÄc!œÑêä¹ğ®G°#¼ó•ÃŒÀóôÕøäÂhœÒ‰ğ±X`ß^%7XÿÿÿQÏQbéÁS‰B´Æ°º¤/²]HÛ°kœOE<Aäá°YõOw¬yF°´¬†Ğ Q%ZYØ¶´H°w¬¸w¸@y´¸¸°a`•w@‘0„m7ˆ+ĞS “ÜÌ4–¤B ƒêE¤ÖÛ`Á¤g `!òMÛ¨¨`¨s¨€2ÈPP¶WæâÍÁ˜>RœëTì¥œ”˜tJÃ–p¹œëŞ>‹P
ÂÊi¿]ØöV¿†3"aˆ„$lIsZŒ¤ˆ6äa»ÁVˆ0&áUVéVŒØØ¶=ˆ”`”â—Æ Ë7”â”LLÎÛ+ëR€€¸+Ê„o³Ûwù;U„t&b„-ùu„‹7˜Ûm/Ì³«8e€ıªïCÒ'•|gÄXöfE
Õ›„c›‹Ñ9&t>½İCÇuAÌFXĞ“¡‚å_^•£jFO² 0Å`Ì'@‘ßs6 …«™©·ĞåKà…İf M…Õ&"şMğXci˜‹mHvı½pô‹İ*:[m;kğeTÁ,.cMì¯MKˆÂ˜ @uL8X’ñ¬{Û u†‰Š‹,øe›ÄgôDŠ[Mè[C.hÆŠ¯ ÆDìR¬!óˆ[İûÇ‚å[‰L‹-ø ¢søkíe{£U\UÀŠ¿\aql%[EìJg+¸Àı[ôUîıc[–‰T‹-[0õ„m ÿ°Š>;Ş^ìE ›L@S, ³Ö“Áâ0ûIPÅ„Å´Ì9ëôv&`Í°°ê©7Âo
4ÇôMüC¸ÅZ,,2Uü<|ÎM•ïS3„¨rˆS¨0‚aÊUª1F8„Éô6øJa‚‰èw‡ì›tÃ8Øü§Eü-èçÜ¤‹³üx¸ÍÀÇ13î€…“Æ#p’‰±/ØaçôÛçèÏ`‡eì›ü§b-áUüê,ğ9é³üx±®ûù3ÑüˆnøœÂX‰‚U±b4ğŒÜµ)
 ?´¯0ƒâb “d¿ËŠŠu§¼õ”Å³FT'¸àxàèrJi0+Š% Bäou{UP
À<ar0 ÷ÿ<zw, ˆBëë4@rX±ßˆÄÂŠÈğÁCŒQ ½¨µÔÁ›Kt¥ø^ô	B­	>ª‚›6"F=©jQSIl!#^P ª…_¼j« J€u=b¹'§àX½¤Ç…Q	P®Æˆ<¼b¶ğ^ÛH%u¡ºùk%‹x¯«c®ô+ËÙ†x  Ñ#ğğtŒÆ|¸*vw÷ƒ½ä:wCâOĞ‹*É-ØÄöåÕÛêR‰(Ì¿‘ª8ûŞÇH¼!±wûìN  KuLèş¯T™’#´°"#“+˜“+™’”*™’#lh>lÈ&şLT"D¦äHHúD|¢ÁÕzÿa? šòø£ uä9ö÷u+C4‹l”R	™È‰ëj$€o`c¿^Òt@$­ëv@¾)*jÃ2KÂeÂ¶1€[„sú …í Ğ ÁÃB€š}cÆ.q [‹Û…,ÉÄöİqÂà/Ùİ#ÁÈNìf:%ÁA|<=t[NÜ}H€NÈ¨$“P¶ÉáÛ ©   £€ D
;N´Å®ÀÆ"w< Å˜C@ëgkxäINlÀQ-pÄ@Èi–ôŒ›N<Gq„ªÄ/Ìõ˜ı&M!0‹1 ¼“ xc_°I{)%(¸¶£EÙŠÂ	y¾>PN˜bÂl }>!á Â±1¬6‘C ìÅ„MøNÅÔşõÇ6 øoCtùBß ŒcMğ(ì`OYÀîM 
¨`—Ô\`ğ>VB‘úAu54¦ÙÎBë
``ğÌ>¼C=³ZM´®†@M² ğp7œDŒ:#Fğ|á¨t€	ìk­ihNH$‰Å‘,HƒÓs7Òb ÆwÀ:LŠÅ3Åê"5<Y7ö€à¾hSÄè]ÁŠœ¸€íú<ØQ•äşD(…$v ÒeMn–lWRe,—U½Ù¨De"P¯à5ºdw.v’ˆ$t]ÂöLğ•ç‰…c6ğ+¿Là‘ÿ<GLÜjQ_"ğ‚À.ğ´jj{)èÖšE¸Ö  ÂÆÙï ¾ßØr$E… Pdl¬-¸~´o„à»–M¸ğ¨ôT2‚v©ZTQÌ«H°Ç{ ¡ÃaL˜‹…2Qªá£dÂQ€±‚?×ÖÆ4Æªn4xT¢0,4F;>ÌNĞ~¾¦ ÜÔ°€()øOLˆ•Ìª÷ü•ºíØÀ¢[hGA|€í©2£7´j1\	X,µ!Õ{¨ÇßÛ}ïë½Vbr6i•=ğ9{;¸êÅŞµòÔÉ^¸*ßø—œë‰‰_3O‰Wè=ÂÜÇÃ;‹ŠcoP
[¤R¡è‰j‰µ@ø‚½’áQÔà@„ãP|Ù‡‚Æ÷ËÆ <|ÿi(Ë|5Rÿ1¶PV´E-õïíŞ"}½=E³#L 6˜Ü¼š@Há©"ë ùZD[ P†XDÆrÜ m@(ü…ˆõ…ì3í5V}[¼¯ûœ½ğ!"`g³X"C)‹±_´W¸‹´Ÿˆ˜Eß‹ÙÏb1Ô;ÁsrË„#5û+‚x ui:ƒÄ±S+•„L½¨x³=~8$úñu8‹ˆõşc·û.ƒÙ…/Øå”(Zto°›½©$ëvŒWm0S„&@¡îÈÇ,—`ƒïzKì‹•x•xTğ~A6‹•8"cø)5ä‹…ğ8cA¦x5…c±ˆØy_8Ü•­fX¥n °¾qÔ ’4v{0i‹tùŸ•6 ÛSÌÅ vl8èÅÀ"µ:ÍßÜl/F£õ"IlÂ€¬8 vê»¤a@Îq ì;;q08ˆq`‡ğÓIiúxv2|êt­ñ ó$[šı‰Âçù,u ¡!{Æ7JT·Ñè»ƒLÀBD(qä d<4d@B,Bä $ d@	Bäİ d@(J’-š\ròJäJØDqÔÈ€„ÌÈAÄ‹‹ÈP¹x4 ÛS¼ädÀÆ
ÏÅ´ÈÙq¬Ï±€ëE‹…¸…tl´xÅáFÉ³`œ¤)yÆj$øéB`ux™LÜ!õì£EütŒı»ú{rŠ…%ˆ…H:€½Ÿ•€x©t-€ßëV9„T„ñ*|Ø`t8J`(Xä÷Š•Ç•D¬ö½ÇÿÇk lö ë`œ*|'Åí5%öºP6@DL )PB¯-„¶+ÈÂÅeÔ}ù€}¯¸½ˆøù½0¾‡@qì•0w¨c\6ÃöÛ€÷v@{
y8	¿ßôê
Œ<	è
°Iü fPÓº’í¾•5‰G©(&)	¾,*èlèè¨w~ëøğ*­$`ôGŒHtl³R€ä·*û7X•`ô³¤2¢¾…_…Pj]D¼w`õWÖˆí™z?Üf–o0)(G•\Ffƒì‹…X>ZRìÖ)põŒ/ÂY)…hõ;¯‚%°Q)½¨s»$Š÷ˆT#8&Õ EØJÅ~¾%ğÉë*%D½ ”ûïCä(Ì‡œ‚rASı† uø;Q4w‚±†hH4Q	,/à±¦+MøêÛAwO;B’M
Q›¨¢³ û.Fuä`9öØ+/‰JÜ¨Äxx8 hs?W3r<QnÊ8{±‡ÓxA<k2¢[wl0c)‹Au«}üpD!¦.Eî`ød%XĞ·6‹Ô¦,…Ğ5öû
¶ª(×P4é5lã„-*›õäŞ„;(z‰B¶âRñ‰Q€__ë âQj¥a•#–n(z ’ÑË-•¹…Š6Š£QÅnâ­óˆH»ùBˆhû !ccFÀ¹OX¿bp‹§4Dw‚£ ÚÛ	:‰•4‰Eg|a¡H0s„+» , èºĞëÕ™
W±Ÿ+åìq(¨Işğ·VµÌ	‡L|Ìÿ$…Uğ€o}ØíwÚ‚ùOÔ
	±­„ïó¶¶ iÎ ŒğÜ‰H5ÔìŸ0nİ+±â ~Et°áMü0‚¸ÍzB4M? OPQæPô:*rŠ»>uAÂ˜œÓ@p•‡¡ÈÈ‹y@‰*Öé‚ p†İ€®O
Éçb« ¯‚aW´[8Q¶PÌğÇÌ(±İı×„Ü;Eàƒ0×ÑĞ ë[5"à€Pˆ×ì)(DG^çg¹±±3îÔE²^ÓàHEØòÈğ İGqÜÜ3ÛóĞBÜ5>#UÀ"TÑW°W°ûîÓê€´ˆY÷Ü+ÜCÚ¨¨> Ñã¼ì¦1".P8è°;BÃ&‹,á>ŒŒ@ìÃP@B _²/%@Š(ÒØ Sè…Áe³OÎĞ>Y Kr‹,AwP4ë	@^P€B8ı»}!VA¾ü]É)İğŞ]ƒáx_kôÓè‰©xEñMà}A´B‡D‡0Hğ²ğV‰WUØ1‚H ÔgbËÜûQî+¢hasOBÁ4ë•AŒ‹aaq4‰Ê=•EàÔEÆvCâÊÂÇü†‚û±ü›©.¼¢Şõo…\zE‚ÈUÒÆ‹0îñÈx£‹ NâãÒt&¤Æ“"UBZ8."­ÂM ‹Õf6aÌîf)tÊ>Ë9@€hà¼!d+bV-$İP‰+¼Ì+s·‚&+Á*&p!
LÄë÷W¡ê$´Ñ)¢4«À$. _-Øb;„øE0¡1x(t7°(B¾b0¡î=IÀf$,,Ø³VÔ“À#Y±¢Bao?çDRÀÊ?À¨ƒH"ë4r=‚ù^`O0W¼ëL‘‰xm¡WÔsÑ¡³Ğ°M¤A°BÛÈ	O¸Ø"ÑN¸‹O°uFƒÂ%$éFÑxIä$ÃMøà¹ÃøŸ‹†‡ğ0ö Ø†ä	ää"QUmÂÆØSáß ¥ÍôÁú˜ÃYhE¨HJÈAÂ(l´Ù@x¡R´‰MÍçeq*†ÍØ6!°}°'°ğ	¹ c~¬À`lE ¬~u¤…ºò –*ÔIğı)µ¬Ìë|ıBÜvè4•4äÁÇü(R;!\¨»¨‹(±Ï 4t[BB„± DQ	‘¢æìHü+¦Ù3nQÏPö‰€Şî„am(«ª}Âb"Ra!İ	ëd^ş»`€İÂÚ¥9#ÚÅ—¢årÛîİëŞ8à·s¹\ná¢ã¾å\æF ( @¨_~pM(â…ÿP$”«Tƒô‹‰<‰—1şéBetTƒ9u! =–²CuÓªƒ¢'j€	êÇ\‡EÑ$vB;H£J44»Ø€­UOzî!`@k³*gåš	N]ÃŸ@Áğ@Fu^=0²M¼éph j'GEŸ>ŠA$Qz$äì£$çÜ+ö3À@¾gÕ±_#ÿQ?éZ<(Ø‡ $Re;E*Vë6.(;ÁÍFP,'ºîpHpQ8Tj7GˆAÄüç¼0„82ˆ|šUQ)ÔEhìän Ó‹B²"î¬¼‡j¬¥èáª98[Êh«ºı¨ÿ$ü¹X	Y; ä4S%‡LìäüÔäƒ¯ûê©ŒVÑ<èadìì½úE8œPÔà"áÆ£ÚĞGÍÑè ‰“ïí¤Æ‡·Ú¤áÔ§”8¶Ñ‡9¤ZRİeğ0+ğgƒpÇbìb‹ˆ§‚KŒQĞ€ò×Ø’)`SnlVİœE7ÔÜÜ²Ø"uÄ>ÜÙÜêÊjXªêbNüU©8dìäñùLÁÜôé„=˜%Úì‚	©P2Î9„&üì)ä:¾–*ÆªŒ Ø
9¤ÈäxäŠ(ÊÈ$õ9äÈìì¢v?"Šì‡÷ÑFÁL·;Êtgáu. <MAÒÜPª6 ½P>c@ôŠL %xpĞkŠ? ˜ kÂƒ‚ä uo4UV†Š~œ5{;!ÿœ‹‰UèY„À`~˜G²
Û˜'˜G~Û	©!” ”‹‰A!ÄˆE•kRÕ%«~y¯ªävØª	ìÆèEèUÅ•A›¶}øwğª"-|Íäğ'³ÛÅğìè9è_/flÙğ~UyqD#BŒPmÒJ’½ê@wğrëŠî%ÿ?ºğ’ğ˜â·´ğÍƒøwÖ	|+,/+ùvu;$V…\¸ìn™e„¯ÈY¼njZğ3ğGQ§mÛB??…-¢ŸĞ-Öbé‹ê‰a<¹uhÁ¨Sj Ù(ñqƒÀA9ìya ò›°p‹4•ûáÆºR{Š]5øVc¶tAƒ‹}ö«s+.‹D †ğÆM‘ &<.hëÌqBQlÏ}ƒsQ‹½"¦r”.v©ı¾,¢#‚^3zV³îÜUÓØWıu	BR#¢	öqOÌp#Å@“d
Yµlg»ÑNà”ã9ah*m$¢0;- WBôÈ¨!YqSám¼ÔøÌÀ± Ì	8„Ñ]cs:´‹QYÃTÁ!%$®0 Î l††İø"?u
&ëĞÔBujQ7Û>p{[è¡$ø¡UR»	bÄNMÈ9Æx@ßö‹ğïœ<ì¥ì‹×_¡á‘•MÄ‹ÄAÈªI.È#cƒÃÁÈàÄu#üğ‘„;Ğw…–ÈFƒ¤…"ŒF¿U!GœŸuÄÛ»Iôî‹TˆüŞŒëfvè‹R°èjG«V‰îÈ·X4Ä¥Ä°}Ä?ÊØÙÈGô”7F5¶Ç´Ö-¤ˆ}ª-ğd‚E°<¸À(IØ´P!PèP8ƒ›<ÀP¸ÒÄœQÛA$$°àg®Ë}ép”‹¾A!@`‰° À®ë¸ŠÀ´Qí€ÄÖ ™¼DA$
! Æv‡Bù«ŠI=Ú<ã£¶t‰U0€ F4ùÊ #™äì+¨šˆ*ˆ‹—ÍbS?uÀÓŒ†—\_Ô!HèÆ…ÕŒàñÏRlr„„'„Âx²zÆ—4t[dr‰ÈGÂ(QXÂ’@+G^ŒàWSéŞåmTGş‹î.·(ğvò‡ô˜Ks¹\ù¹úÅûğê š®ƒÙ·ßÿŠ€qJj è
VD£$0)	^æÕ"e*U8àdÏì•‡"^\ôÇáRĞ‹ $ü5$¢g£‚aP· f]D™5A- øQ¦‹ÔK?L…¬;‰vŠŞeP•#à¯ˆ­7½%Ê§¶Õu/^ €šy
=Pk”˜g'Ôı:Ç…@±c“ÿ½w‹#CQÚƒ|z€× ¶w×‹@¢›=;öF;1s*‰…ZM êÄ¡é‰²ƒ°ªtÂ|•ZØCÄX4‰(‹¡^ììØ;1v*‰nÀö(*Å1ÇGÀVh*Lg X±ó;‹ıµÑá‰æ;N™aÀs+‹!+]#áõïƒ½ }
¸-4ëª„,Ç‹…*D•…‹½Å,íz¬Ô’ĞT]n ²ÚA•ªuŒU°bc› U…(úh=&/9BÌA²ÃÚhÒÛ<Fì DU)šàNë@b’’}ªƒ‰<gA2”#6œ‹WÀlì” ,6á3+„• _Å„Y0N˜(ÌÂõ²-û‰r’‹`O‡ôXï?M,‰AÏ˜İ…ÿ Z÷ÚA1JÙNàöªHFÿ1„I
äğŠÇÆˆ”š;u@pˆ=œµMÔœ„ôøôîñ½ˆd¹9D#D½C€g±±ï‹‹™—‹µØØ«+>‰’2€Îñ; vÅ‰~ÇL‡e!‰…‹)vû‰-Ÿ+Q~bÀ8¸ØàføûT0t9†”Ç^E‚-ì+Â/ŒJ}#Æ‰•(;xsb•–›QÏüCEHÑàMß,AdM;ÆÒw¬|}dÈÎ ¸©£}(‹dúÈv-ĞA›düEø$˜àŠRV”fèbl¸0-p}Ş­¨N#Uƒ½'é*D‹…(‰ŒÆ>Â@ŠjˆUíŠòˆ'ã‡†pP+ ÓêA°	^>*Œ0ãcÑÁù+Ì
RlÌd„Ğ:øS;bÈnTÈëó»DÀ»|-TŒ„Ë,…9ÄHo/prÆ„Àë^n;Ã%‚¶Ósí¾: "ÕÛØ p °`¤ ÅÊUj?‚(}jìë.+Ù‹uªcA\‚PˆJ+Xö*èæf/áÄNñ+ëÓâŒYO˜4]Óè1€ãìëÑFdì 0MøôÁ\³eÁÈf”á{2ØÓàOÑéKà¬+ös#QKƒ3~ÁëÎµ3Á`Ã¶¨â±“Ò¿#…ŒMxÖ;jt9£)Ø#Eˆè¾‰…dQß†Së¬²ñÃ¹–"
0½ÂM&ˆJõ‘ûëYhøş£ ¡à	*Şª`İjŒPÊX(uàÀQqës{PŠ ‘ gÊµU•9;Ø€Nı®(ğ¨ı’;î ºK/ëûÇƒ9ğn¦ªÈcÇBU,ÀEèB'ÔDèy¤e+_¯BÎ>âh <(²(CËNÒ“]µï» İe[ „šQĞh$Ñ};€…À±M®EÑ}Ö‚Îe´ÜëühØY3ó~à¼ÆØ6£(r“QÆÑĞ,ˆøïÛïq PˆôİpÁê|j OˆLQÕ¥‡‘‹2ƒmudÂ¯Û³v[c‚Ü‡œë0cux¢‡%öQ€”­¿\“œLt((ëe@°Æ3À/§8"ìGİ†JèŸG4×BBj?¿HnxŠ‘€Ì±Fõ ~“B}ôÕÅ Í%ÈÈbÄ!F;;&tz
¡uU> ıRvØs1‹k—ÌéˆC(°Ø‘ğ¯û¹¤ğØØëÉ#IÅ´
9äÕ§ŠuDÅ„â7³¦¡˜=Íğ!³Ø¬àßØ‹ÂäŠQ`€í˜EoÈC%\EnÑÚâv  áó[R¼àœdYDŠĞ‹».¢¹AØşs%Åå3‹U&Ì’¦‘‹„ ÇĞ<	 ¶lUí„Ø;¹‹©0¹Š©9]p£‹;ÁOUƒ¸Íbï‚zEà£aŒíÔøìw+Å/øÄm;(rU MèÃÜ€høùÈaUø¼S-‚Ö(Ç{ì$ˆİ&;ëbb‹Z+ÈáÜÎÎ—à';v=|+c3DhõÿT‡P 	éàMàŒŸ]ÕŒæ ­3‹ªßRêGâ@>Çâ©äåää‹t}œğ©Ğ€¯5êä‰°=å¶I_ahßh ÈÌ0¼ı!ìØÁéËìs“A†ø!ŒÄë:ïŒĞcÄ.ÌUì]+‹¸Œ»ü›ÁáúÜCŒ°H 0Ør4çää!rV‘““mU<&í
g>eH›ÔÚ~Õ|+BZÁÃVQ#s Qš6
-ºwkÌj‡.;U9ö+D*†À9lÆéPp90
¶Œ›(ÁâÎÂÍh8DC ğ€BØò‡€Ì€ÈÉsrÁ0¢ˆ9İJ&€¼£HˆT¯İRM´Á	°äÔ¥r#ÈPÂÿ}Ì
ƒ;úÿÿU¸ Á4¸·U€Hµc@9P)~÷R»ğÿ‰}‚n,hnÂ«
gQ"zTÕpWÖl3J(°e+˜  @QX.âP|I•M­‹b‹TË?È•º(Tc+1rÄ@–„üF ‹U„;uÄá,&Â¿QéûĞ $ˆ‘ü
”àï!lÏB†0x¥‚nÍ°ès<ğÊUÜÁğÌİœô´+Mô¹>‹ôŒ¦Ù³üÈ,1º©üø€\ÖøèC¬Eü“$Q25)\ü„½  ^JGÈrr„!!GÈ	
r„!GÈedc€_ôÕ“‰”à½E0ØHL	«¤o&D€4j±‹B5 Âñ¹ñ}6Û3÷ñ–øXŒ…P-ÔÁ<¡º@¬_¤¸A¨ï	;pŞ…UtUC›O	 p°G\¾ $[O¶QàxçğëÌ60?%¤°‚—Í@„KVĞÀË
°¯¢€Ê&PB*HôPÿG¹ _ox€5Y$xE\©ø(!l+ç"€LÁT¸XEÍÇ ±‘À±]ÃÔÒ…/üæRƒPœ8¡P4Š‚î¥ßÓ;Êu™8tºÀ<ƒúƒˆdñ0ìgAu
¸ş ¶„¹ E[r@@Cîà2Ö%(Ö
$)új°88øj*(@l±f>vsK‚ñY ¾ø }›T;°÷Ù¡Ş=|Èø~ váÃœöZoJÕbAW-N
˜-qRjÏ÷ÚÒ÷ÒÙA¨Ûâ¬`©è|¥DµÙ‡ŞB*Ø Ø$hÏí`	³Í¾3,80tb@Cò©Thı8­´ĞÂJBûP{û ĞM*i™ªĞ­‡)şh±¹'5›^D±TøFİ¦Ö«ŒÓn ‘<­›LjUÆQa2+|‹]€]ƒÀ+ùt(é ˆ
:Rì	<„LéEckHÁ¤;–ô	hv(=ÇBXÀÛA8BAiÄ	{2ƒzÚE"´Yâ°*`Cœb'u†Èüõ6¤Á›àÆ¿z k…Òt(@ ÆwŠB V@WPW ËÎ"@İ1œ°'gàƒx¸M@±¢a¦ûBÀ
&Røult*•NBÀ\HÁó&"bB!ZÙh
R0E$á0øC:ÌÔáÈdÀÆìHÖj†t3T‚ÂGÂ`3„-
÷
3”QØıABƒÖFÛ¾¢M0çŒAØ€¸Î¯¤7ÉPX ,¹C˜‹Ì¦éã1¢^4ÿıuaTŒe!E<»ÙS#ætÖ`e·rÀf{uÜ/a¹*9’Rr‘°”9½	%˜ôÀ¦ iKá
!@@ÔÁRIIÈV¶%š 5Në!ó3øc©Gëõm/à‰NÊbíšKs¹ÈäRÀšæÒ\Dq~ìtĞ\Zşf¾Sp$oSw-£té ˜AÇ ’ê¡€R¸ ):`?c1¶5
…— ¾êuCÉè7¨A uÈ)"Ø!²
çĞ!\Äkà<ìÖïO jë56 é†Hv6@´ )ğ² n¿ˆ”Àˆ°j˜Ÿ±hä\ô¹uMàˆ¶AÁÆW'8lˆ®
%WÚÕAgİ€’@€XAÂ9‚C‚å4M;€»%ë3FQ:õªí¬JÏ&¡¦w>ôkôè#ş0£ı_,<BQ±ƒÈû,*c§X0©ÀÔ1h~üRébĞ$ÉÏÄ«Á3â!¨WöÁVÿ/'ØÓ°fòúRõsg·-+Äë&%vLÙ·ë#Ú]ÃOt’}À€hŒS³$¥÷±]bQ4ÆëAPñÀáÈ$½j–uÏ6ÜUQœ¯`ìÙ?y=¡Ø”ë,g?±f ^|¼ÖaJHvr/Á¯¯EO]Š5 ğ“PP%Œ¢<†Q†şÆ‹¥‚³QL]û¾ëb&†ø;JvM§ïW{P+« Møp7A@£ÀrÄm‹1$dø
"BE°ÀC7%Ÿ ì^_^ÊAÔ+"+t8R@ åC‹ Â«XVPøĞ.ÊqjRŠ<g õáJš Çuƒ$!\õ)F™!Uøõ‡ X`jvxæ€ø½&
‹sÌ¨€WÔÈ°%@Êšoö£Z& R&8(E¿L¬öÈ‰M&UôL&Øv¨\Mé&Ñt4€p
f‚K`w£Ç½¿q˜$jhTáÁ¨zc$gkP¨lX¨*=Õì;h\¼Q¦*ìh~0¬mVU° ÷@ÈT Jıôh¾œÂøEl;Mv3Eô$:›‹ŞÁ¦{kÀa+GEà=gèuÀ°ÜŒ€ªøÜÃóÅ°Ş`Ç„/$ :LŞäè÷7tÆbƒërrLûŸÊ.¸­:~Lå.x‹DÉ)Pu;½İU ÉŠQ¿Ku+]=èıÚÙîi;Ùu.B/áà/©Ø\&d¤ğGâ=#L1N¨€ .¬ğœ¨>/ìœïAORL&{ğÂÌ„ø t‰$b8üfQ„ƒ="Ü‡Š\7Ğÿ 	Kq0L€ "›Q—Mü•–ŒC6¬Eè×’ñF§†öÈMäSƒğAµŞ;…;,6dì‹+™8„§NÙ°SEˆŒ•l4VÈŒ·HıBïˆÏ„;ĞsCğUÑ°s¶ê+ê…tëf
d7ÃŒ½>HÎ>U„ÌZQU—š ÀV†€¡Å"lmXì!â%*ô¹3µH¯+ª@ÉôÜ 0Æ+¼ô/À“Lšã,â}—y|SŒ4ñÊHöT´s3À»[ ïRÁèUâ@Ğ@:ƒÊ™Ø}à²Ÿ‘ƒRq
ÁàÒîFj şê	É¼FP¯N¢Pğ£Å‘¿U±È6=:@âÑâÓREI?pVW?#3ñî©àØ+xôğj¯ƒLljP‚ŸA„ÆÜôÿÀa$»Ñè/ìP"!ÄŞ>Ø	"ë}K4Æ²ciEœ.2ÈLÄ.‡ƒä ¤¨Ğ\¶²¬¢Ô¬ĞRñÏ&í]²Ø/5Q,U´-9H¸¼’ƒì” UÀÄ 9HÈÌ¬°ƒìĞ­øÆhĞ¼ ^— 
ÔŠ¼;€†ĞEßS¼0U÷¢"˜š¶Ú€˜°v*àƒ	Ë$Š6˜<½6c­‚µ+=c†³‚`Œ¬A¶h iE ¡MpUÀ­M”·ñi!’”ˆ(VüPŠà‘³u	àìŒ]F¼À éUC@¤”¨l	ìØk+îàEŸº¤À‰EªÚ¶|$|v]ØmÇM([$µÆ`ÊPÕ©Ä:	ßØ¡!gü]‘;l¡šÄ¹Gœ(ª2`¯ =šĞV†Ñ=¹kµ7‡uœ‹ô±0Y©¶8¤XüÏ$ '
h7 †‰‚EX¯€.§'¯$C ¯1W£X<Æ`C4“$
Mø°³‚Ÿj°àxR¢(W/ÈM`$”Â„ˆS èøv²–o¯,ár$Û!J¦œ+ğ¾‚ø;£ô¿±§œnH£QLD..(õ~AP‘ÊÁlsJ*õ%¬;ïº³xdCb;ù(0ºFÄÃÉÀÀgA³P±¨oğÃhHˆöMÇ 1,/ªRãìÆƒ2%ÇƒÈ(`Œ~á/m/ .¤§	ãÒ@Ü|ğ)¬eGàü|Uè.„,ü«Ødl9h¨ı51–ìB4p’4–íˆìw4ì”À,è˜üÿˆØd#r"ZqûMrH<1bKd·â#Òu]@	ä¨ä@D¢Â’ô5Š’°à@HøÑlBh{Š–@ñî,x>TÀ Â4¥\b{ÀØmC
é?O  È17¦O¡í²UzE@„&ëÄwÂÍbäR&ôŸ,!›#ÂAÑ:™Kèjl–hú-˜h @¶
cß·Ä‰N¶"ı
Jğ‰­EL1&U«Æ:¶øŠø/5!Æ–Q-@Š`XÁÙMy4ÎÄ¸‹¨PT‹p@PKD$ÁAĞ°m°d
`m'4ÖRhQ.ì}@ê°€À…ÊøáaİE#,£Âï;v	ğµ
+@r–Ã¶@[XD\õÅ‚èL{<‚aU4å.$!6{|ÿ¿0V 0 „d'gS»l4.W|‰U|- Ğ°86xàA7ÂÇ<Q9ZH’r¼4ß\væ\âÙ›ji†Kÿz ûÍ ïaxX¬ÏN&@şQXÄ4	iXØb0,w!Y<JàØ›hP`PÆâÙ¡Q`Iì²Ä¬'Ö%áßg<MìUv+…‹XŞ;o4Ÿƒº
ìËÅdŞJµ @z0v!s=eˆuù	šr 	‚@g9äp‡›©ˆŒİæs-ÿ»	uäŠ1ˆëÕ‹QXõ}jQPâÅÍÆ~öø‰A÷\+Eè\kÙ!#‡`22Ø~À;KğĞ–*‰ŠƒmäHñ+â=ĞJ*ĞQfAˆ—ÍŒ9',d(Ô¡Ø.²†Xñ|ÔÅÜ	¾zC4XÂG&ÀmÄ‡‘ B·#ë!Í k7Ò1È8ëÀ^V,_ìÆ`$ë5Æ÷-[Ç•\Ä¥@0ÒTR—3 Áw9ÒA+ ¢1qÇøñø€‚!Áû@ô ’`()h	˜îtŒØ €Ez|8À	$][Ø>ÄÁÂláÛ,bfGô™Ma¾¹ºæEf 
Eê‹M]£™®›"ì
p î Şv#ğfÚò  F ^äãlÇU
O¸¨5’xs¼ÌyÿÌ6Ç1:6Y:8Ğ.(#hò×‚m4@¢à‹Ğ‡ ÁÀ6£Y»×o9V!!MÁ0ø\ƒÄGõë0Eç¢$HR­‰×kîÂ¢z øŸ‡`Cïş.E¯ Äÿ|)»ö.5Ü|é#é=QÇJ¸gƒxÿK1‹BÅ’Ú.©‚AÛ×Ô‘4c Zt×µõ
àc@Æ¹Kè¯Å2àZ©±,F%~ ¦;è]DaÆAøÇ‚°g£,€oÈåM&99 ;$(†±ƒœ‹•’pCˆÃn}P=p¡+$;º}Õî,, ëİAŒ€†²Ê„]8­Ì¨QK†‹‚®O m’ÀfUõˆR‰Jøñáìé%}Œ  àdOM— ˆ)‹ğ İÀ&‹Mœêd·…@‚ƒ€÷`õt`tYœ[hTmÃ7ÒœñEİ¡•<¦<€-£‚şX^÷ÖĞ°eJ¦½ä¤(ˆNôë‰MÜ‰uà ?‚ÉÚˆ½¢ ¯)»ŞcAFˆ…hO\áØL3ˆ|äÃnâ2|ŒC9l üI°¨Æ˜€mx‚ˆo@o‹lEÄ/t„+bŠx„üö¾ÃìˆáQğÅ Šõ”²R=O ‘vd34ÊÇ hfXlÉÍie  Eøà±$ˆ½ÖÄ‰‚®XÄö¼¸ÖôAl*¸’€h`˜Qf±jë f˜GÛF–±ĞH ¤¦]€Ò´ÀiÈFL„½£œI„ì/ƒB¸mñ…`„-•P¿l"EHˆQwÆ…RÑWJU»É¾/hø pL/Fz5’¨½¨K·…¬[z/ğ#:(F€½1 o—İB\…!A"#ƒ…­×Æ~ƒW0@5ª®ıRëÅpQ7<ƒÚéİß‰‚íŠ ¨¢}z™‹¦ÌvtP%ƒ„î:¾ÜV CÇ›9‹/­\‡b­’°’Ì!ÂeÄ¶X:`ì( \›˜pM v…LË½ÖTµ&F„D	êH}5S…4:±Qì8JKğ	±	¤§‹…T4;YD tMÃ}‘Ã6æ•H‚QÙI#—[,0Î‹7Æ‚*‹-jÂ€ñ•Xş°Dï»°±¦ÙDôD¯$SbD(¯$iA´fÄlé„Rå¡I•N#@l ˜á½À2NÇuøAANì‚x3Àc°$(cïˆ ïŠÀQ·¹‹ğéC\>”V•ôË¡bĞ•: (ñt*rådÛf
¦\¨ öü‰1‹ìqÌ<…ğ°{é;ÓtP‹Y+¿6" Ä»KıPŠ^.Q0ì"2ìı$bQp9âğF¨à=Sğí(ñSZ1,
¡[À¬*FqdQÊ‚A+½µ‹ ?hBb) dŠX½wÁü4IXt%ŞÉbt0CêÕ~$^Œ!x+@„¥ ³XŒæ=zLâØ,bëLÇB,‚ÍL9a'¬_}G+¼\$^Å.‰Ãn{}P˜‹ÅâIÄÅ`/|È‚ª¾j,$1œd÷{->¾! ~’ l-Ê0`µ	×-Õ7ÉC([œ½œ½ŠX@)„6Â°%	èa|‹¼œ½ $/äœ½œ½fÁØ¨—¾@¯—ê6.•Ü¿ÇÉ]Ê0úÖÁc±RÇ/+Á8cUÆ"/`çFqÌ+BŒ·{ŠÃ¸‹_Xğ¾¼•÷ğ"jö<‹%2ÍZ¡–‹4[‹(C³ß	 †ë·TÅŒ6L‹ş}eU±T˜½áîè4+ƒ„¯,Q3P¶4$µZ±F×	£©úmÜ±ù:º½$P±˜½ğt¼OŠ•	í´Ph±àp=I= %w!Ïßûã>%lQ{1ƒ]Ä½-|¬A{•Âm5‹…´¾MvYÌ9ğ˜!X¢…j‘T=½P90~|“ƒ½ÿf LÇCºP,ÅÈĞm õˆ³ÕÀÃ Í’o#+¬
öÙß½ }	2ëIôfİ%å>j<¨¬<ÇsüJ@Ö€bºøF¶-Ù/ uF=‚
Äƒ@Äø„‹I…ßx±ĞÂh‹…ÈÜ˜12Ä¾±Hk"Y¿}ºT‡à¼Q"ëÀVÔá‹]6¬¨ª¤@ö¤üºÈb@¬`ÇŠJ ˆ' ?HU0ÖöÖ€a M5•  <üh&ŒxÁçÀ3À $?h<¼AÉá(°(8E`$ÚÙVU	4ÿ%R²ğ<ˆJgüO¸€P:tÑŒ# LP+dCd‚Úú6H†
ã©-{Å(j…¤ø‹0€øÇ ÿà64Q²‹}YßÇ[,˜,:–ÙëJæ9ÄòLV+@$"íÁ7rla±>œ¡ØB‚ïo^¿€\$?_Cüˆ/"6!Á0ªÄ?h,‹ƒ;2òëN¬480V*ı¼»½`…ÿ5À`
cà‹Y‹í½Ôß\‹ñ+òƒÆtÆs=eª“mDéP,`±~g/DIöë,0+`£FñìÿÁùˆ‰GD$5?Pïƒ-`‹Æ^WSÕÃˆ$éy[²€ÛYHÃWXY}ìîcGuj_`¡˜ƒ  ¡¶“‡c\Ÿ‹LaWƒ/A-7½|$„3ıÍÀiŠq„ötOuŠF8ĞË÷Kk„åŠ

uê¿¥õ$_;Ãğuë~…Ö_áa„ä¾òkÜıàuÄŠA1Šf}×®½tßë±/4ŠÂ¯dòGÿÁ(àİÃ‹Ç£€È˜ìl5Ø¨uöš÷>ÁP–u;j	ÛdúámzËÖeü ‹hjYÚO]µV}h•YèØ†ˆOGÆ¡ëQ1°ø{,YÃQS?gÀ~­
şÄJuÅv¡{€lÜL´uà¶PİØvVRH­E4‚º<#<YƒLWVj âLQÀXu eÁ-ÂÉqQnuın¸rX^-…sì+hËĞíÈ‹Ä‹á?XP-Ü GßWVSÖÉé:L´HQ%ªş›–ÜxN·A³Z¶ IÌ|{á&
ä±t!
¸FG87İo\8Üw«æ8ø	ØÆ8Ä¶öIu×3ÉM›	ˆ_ØW‚Vÿúx·÷ÙIvğÿm,7À( Üèl·Ôëjhtˆ#5Ç™™·ÿ^Ö3Û‹ÿÀŠt#ÛtwQP.æŠ»S$v‹	ĞÊÖàY;ÃyÕÖ`¬¢urX8Tv°]ë_K¨/ñ“Ëõ[^_É_hU«±Ã-n[´!ABOŠ …^­µı	îøÑR¿LÜÇü5ˆªT&ßIÅé†Yõßàw"9IñR¦L&9_!—ñs°€AŞ3À”°&ÃL ‰CaŠ;öH‡*(ç‘˜k¾806|˜Ä¤mjJŞ‘ô]äšZÌ3pƒædüìIj^‰Yrìw.®£@+ç÷#öva]·u-Vë•ÕÚX2^YuQÊ!™0¾†$ğP§¬”"p—àà-Iö™–Ü®ÇJ~Œj$(À\ñ5~€æ3ÙéÜlV,$=B+ª_ƒƒ±ñ2Œ%ml‘£”xğ¼‹ğzºµ€–ë}j½ÇÌU(©ßQPöp¾ í‰ı¢ë
rIW%\ìuaésoYıBí=IŠ¶ğö†æº7¾9¿!ˆGA¦bwµ
”ëÛöÒuÒ»€gÿ~şQÂ¢@·…J#^0J—©ä«ªfjüã®GŞY_]G SUã`± "¥uD 1¬(¶!åM”¥ÿò¾m[!l‹T$Yf¥ÈBö‹­sÇ©tfUDu*bAlÿ¿g*üC\ŠwÉ.ÿK-ÖoBŠøŠÙ‹ûf;yrŸmïgwà1r(·ıØgwë·:‡[º®qê< …¿ DÎ¾íM ÁEö€Sn»óE ¶„ª{rÈÿEŠùŠØ‹óf;5552È!‡555:2!ñŠ†¶;şô:0\†f…5ëmºa×ôLRw!÷YCƒ½¡Hë
Y©­¾íã][%V˜9akxc¤ÿuïŒEëRWö¢‚AxY”n
ÅÙÛÂøö‡àÂßªm4YàÇvá%ˆ9q`…èkà¶ŞHñ!rQ„k–r*E‚âşJz„n ƒd‹Šd£¥-ş¬Ø‹]‹cqm‘
¼§àOåXY‡/³ŞÖABT±ß:Q(Nyj ¿±5Ä³üÂğ\€@0ı-Ú,.-[¨‘Üni‰¬\zÆ~ºOüÙPPPCĞ g“-L™¯Â@?*DñİEƒˆß!%Åeì0@!*Ô°pìèO*@øÚ ÍL-ìö¼à-:W¨P¹ZT¡:n¤ÁÚÈ‹÷Á²üQæŞmkÇp{pĞƒlˆÁx4ëeØ7roP6ê=xàÙ9¶¶¹ˆE®(eô{{¶iøü ×‰‰mø-YØÖ!(ÀÌ^=ZWE€İÿ0ƒ¢è’0£ÚeÌ²½4IXØ2Ø†À!ÎÌaz±Q8İ’‰f"ñ ÂWŒİÀëMÛêp@ˆMˆ
pÕÃuxÈ¿cg¢NM4k ÿcfœ"pNCjmû€)‹w!_LŠxƒƒÆ›|9ƒşÿBÑ•;¤ON¶9Lw)ÚÚƒ.é~âçß÷M¥4G}Ê#S &F‰1Ws³};Gw£ğvGCwÁÙ,\A«òUq šQ–]´±£x÷—2aV$KíÔ½àÜt¸Ã&âğï-jş@dÿ5RB[pADX/q£¥»©t.;ş$@4ÔmCo4³‰‰H|³ïJD³} +úöQÊÿTëÃd© …Fg`ŒÃôÆ¾ğ­á=yhrQúR9QKp‹m4›ŠQ»µ€mÁZ?ñKCmü[kY[Â nµ0hXHdìvf…Ú·ıæêC ƒeÃ,ŠFµmuPˆRáËÀÁVÅ ²¾k»uëÃJtFëÆ¹P
öë%F[/Øµd]‰@+ÁKÌTãĞ#Æì™PNV8Wš¤\œkÉ6%T	?ÖŞ×mj]WĞAø±"vk[Ğ
ö‚°Æà tW€9 OkB_\ˆsEü¾éXÜ–ˆ¿]øS°İ$V|ã ZAÉ5ÊF1(€…‚+…-ğ.f~EıÁçüøo#C·]`ëˆeèø€á€ùæ
"¸æ­9ø—Šôœ›½Ğ÷—S€>+[àŠuaŠôÅZ†u3CÜ¶œ†æğY\k`xğ--‡°Á,?R¼Œ¸ €C´Át[Éµ…A{G€Ã±a#8Åa¿ô-XiÉıCÁÃ&ªØZ­™Á4B±¿Q  G´YEt ò®õFĞº7¥jÃ¨D}7-MöStŸè‹¶
¨ëÙ3À¹Bzk}¡Mhr€yXÀ
V‹ÒÏm7¨·…F@£f&ôm¿È¦mU ø¿{Â
M!Ç0ƒt4$|ò¨J§ñÿvğØ‚p­›%^F6N ãfÙYJödKâmÿNÄÂ­­5xUŠ[kµĞºˆk"å ŠÛf®aY(¿Y:ƒ-4ğ~ppS0#©·ˆ€£Sœ»¸9¤OCVWÜFÜ%}£Ø&İ‹şV+İøíµ	YÑY8t3w/öo”©šnÖ+ĞŠ:Ë·¯½}ã8x@8ï8ƒ…¥Êª²Í?0,FšC¹V€Ñ–~3½÷€? tá¶Î€|lú¨77ïÂˆÀVñ7\DC(2•øˆlc|~¸ˆ.ëÓI‰Éâi{ÒF?§¥D0`Çlğœ@Sb¤5„†Á #+d°¡äàY jx
Æ¾«i*A‰ëÒ*•ÔÛ¦n–t	7<
åë×;Cx¡Íƒ<Û€' wÚCúÃ^_[]«ºm£àˆ1ğÁ#¡Â¯á;ĞtÃ/®."[ìa¡PV©WÄ.c7¬¾SÌº xİj@:A:=Ã¢‡%G®üX/pmGMóÒG‰ß¨›GŠÉÑe
3° ÑèÒGŠÜèÃ8ÓÎF&Š¼Å¿Åğê	3ÉFŠëŠæÙf9YÔK u	f ë£$«X-%]ÃlGTÃV¢RÍ1Ñ'öNÔaµµMzÉUÀeôÛ€8_F@ëäu^ Cz$]‹EÜÒğcâ'"Å?C20XC00Ëæ‰Û‹ü€`÷@Ğ#b¯4QªÑDşÄe+*!s{­avmñÛ%ªEVUkT®{£»]^A¬3x<%SmôÛÅPôyVQ6‰5°6%DÊw®Û>‹U;0‹4Æ¨Fèë¡¸€,b“ı\jÿ?]€[ÿ8Ul‹)‹A¸Ab÷;ÙP$ÂëYÈ¯ĞŠ·ÿšğ8	€ÛâÃÃ]³­3¶»í%yä	°Wyàèº‘ËX½	ì¯ığ¥(,ø"ô‚-aŠ-h½¶wx‹zà×ñ‹ØÓ¿Ò5uÓëA\TÑéváÿÑÛÑêÑØÉÍ÷ó—÷d=ûî_º!D÷æÑr;'wr;+†Ö¨v	L¡‘â…ÿÈÄô›Ù}ş›f†ş€Ì±½Õ@üÙVß}ôş1YpaUøÉ©È¾¥I>¯‰äwÁ¿àwÚ;ó^ƒÆî]Îk·Û‰]ààâ‡¨gÍ}ä·`Á;EÀHw|ƒ(üWƒØàz[45â—!àt^†äëH,8° QÛAÆÏdƒä9K½])8{´,Ó–vu>?¸J|às1'ÛªT,¢©Š¿e€AYï0(v(¢ˆ‡†,–É2 [­H´0(®†}‹ŠT.\Şƒ7±İ>pj‰I³G@W¹ßnUÂM,O@¸¿€†§~áÿ™÷ÿ‹ĞiÒ€ày*ò…F(İ_ê€3áYğ|+ğBÃ°ıŞv…âBëw¿­°½TÆQ™BQA}£´‚zlÆiÀ€®µ(]Ä^¶»*|4…_Pt¿õ9}ƒÃëöHUÅ¿6j+‡CB{7Øuz Š_j1À“ŠWÊtÀpÍ¼„ZğñÿVííMkÀ<‰ƒa "p,ãÔÁCßÕlÅB•€ztÙƒ7L¦Æ©Ğ¢Atuoë!¬£;(ùIt%õ)nö¿öuë-Qƒãt ¼—âô/Kuó#ÉÃ÷Ç(c®ø+×:„Šqu‰¶¹³î:l.ú*ÉàÿöÛ.‰ƒÇc¯ºÿşş~‹Ğ´D«3ê–àKV©êtŞ¿,E˜xƒª÷Â5h¡ ¯ºq[¶ Ñ¨—	
Ú"€	h…X! SßUí½şÀ‰_í°u…< ñ†£z¬ˆ«0XT†¼‚DŞ[±ã|gt<¡÷{¤¬©}%\Á-:¾ß'„‰ˆÁŸ)úø€X×@z5ÑX|rV¸†dÍîãÛ¹š&hç„·M¶ôj¢”·“ˆ©dë>,öï3Áu,9Xd~½ÿö}?`´_Ñ2@hld(Ô}:ë¹Q£BL¼xíBÂNø‰Bİ„ˆ¥>ª	df&õ´Ğ€Fi0"MMôöhCW|Ğ/Ú9±F]ê­*pm¼N ¬3m*€V¹LWPl{ƒ#öFy&4!"‡ÄE5[w¢™
ö)œ" Qpqì˜à—tÛºÌÔAø >»hÿ’R°€hİÎk2R˜MJ¾½&®$ó`—	¤~û j ¨ğâa,àKİ~%‹JÍ`…_982õz¸‹ÑH1`+Àô×èÇîï‰"´ˆ°uÀ`úeªmÅVqüƒ>k\çr®šƒîµØ÷ı`sí^’0(—Ãz)Ø÷<4ø[t*ÆŞ‡â_Ãj¸6[„Jj:vO½hox;×s—ÁKÉpëí^àßxM (ô3ÿ;ßË
n‚âõ‡ÖÈ±cÑô¿—†ÁØG\Ï9ƒMH}Ü%ñêxê¶üS‚Ø;Ç$r _ | pwLíàpsè&<Üë8¶(íÜ-Å\H[£õË;Ær KÜpÌÍv¼\RQ@h• »m>9|uKŸZ2pà»µ¾Îò-V#`ğE¨ÎOp…Äíb<XØı;÷—¨µ¹VÜ‹ƒ‡YxY½*t20[ê±ªxG…Cğİş65†Ú‡bGè[1‚kvƒÆ@á’éXóÔĞaTœÔãÈµŠfœ¦-%‚°-ÿ}ªh[m×ús\‹ŞÁã²uÔıœbŒèyâQwH4uShÜ*à¨X wÎà*lÂ#‚ "CrµôÇîälÂ¨Sà¬H=+ÂSFëP@á ˜	ÄcŠZ-&ufVHR&4ãKOPˆÂMWö…„„ğH´±’ÒP9)eÑ3Àò.øjîF«„ßZ{´°ô‹vüó	Íàª‡Å	uÏdJÂ„û€UFæ¬:4SÜÔ‰…#ûÂ›ØVb0ÁæVàš h Ò.ÂD£„V\ç„ØÈézÌ ıŠ.ñBÿş¤&dËD ½ê¶rä`ØÂÃÂÖÕ$µŠ
B8ÙtÑ±Q[©·~uíØEÃÁãá‹¼ÅÄ
¿¦Á‹÷3Ëgùƒ­â'Zo¯Ï3ÆhŞHüÄá°u%·Ó›ŞŞæXuÄ»ø7 C"‹Bü8Øt63ï8WˆàÜt'Oç)¬İ4MÜÔë–-±pN:÷Bş7ıü#A"ƒ&ïxÜ‚øf8MZMH<ÿ—p¯ŠHˆŠ@ˆF# N"¸,8Œm½qhÇ”ià*n8 ¼|ƒ½Ú.€¶®ErbX 65$ãgí#Dp+6o“
z„(Ì…ÀçÄŞïÛ 8B<a|Vx³P-A8BAT}{j>¿ÁzŸdëIÁ‰ Ó Y|)ìöÚO[dşU`œ}PBPê-Üïw>j,dìÈÄ·`£0ÅÈÀ€9;8½Ûˆë;òj
n¿‘ÄŸ*Ätôt€şşœEüe¨€ï Ppì÷ÀZ»qêFÊ‹vT1ÔÃáıs_áLÑ1ÕDPuh»§¡	sh"Yë
siÁ1`tòÃĞeD«©ÃvV=W{C…'ÅÀ@¹o ~@&ŞôD‹àØpóQmïnîh †ÿ6ÿ×€¾%éÏ¤
ÿv ‰ÆC;ûecÇF|ÎD][ë'’^jê"~RF÷‹F?®Y°JØê6rªZ0°å«¯| ÅvŸèDu*ƒ%< <Ç¨¦SÀ8ãHg®jmÀ0}ï@ Aì€Ù¾ß‡I+PúÚr HñµÀëèøµz¨#•SÔ‹ƒş¿-ŒşÜü+yÁï‹ÏiÉ	o¨¸ŒDNIö @şPæ¢!ê;11éV´Û‹öÂäñŞú~Áú¯ú?vU?Z‹KSÁ·o¿KuL s»÷ÿõÊÓëö÷Ó!\¸Dş	u(OûF!<!Jà œ¸Äˆ§s!Y
‹ ¸õİ_ëSZ[:w‚š§Z5RÔ„u¥Ñ]v?µÎ–ô´“+uøÁû6ø&Î?0K^;ŞvpŞ»Ÿë¨;ÑC8;ÖÖ;^¶&èÚtcâq½q@ƒût÷\¾ËÓî÷Ö!t¹LÆbÿk¨™1ëKà´‹µóÍ·DJ™e9[`I€NuâmuÎÇ–ÚtõB×ÜVø\Ñ¼“^1(.ÃÛæ67‰@;u`ŠXÚıPˆMşÁˆs%Oì’Ğ ş_u	Û%mD­	á-a¬;$f%UÀØ#]ºê„¸	€şj‰D0š_'ÁÂÿ9÷Ï<—Ü€?şx¡v4‹5ÍÁáûH­¹»á?SQÿÖÅ,62[	Pî†ıƒ9@ƒ¤ˆÄ WşHCö-âÏ
H€yCƒ`şÄ ¢ iRÅ–êøÖˆ‘‚)õĞàÆ?ó€xß8¶ÂÈ(+ÈKìQ„©¾ZœxÁ€¾ôôsß…@;6<Úƒmï‹H‘.Rë=¤`“|<ĞcZï])0eW<‚,Ê­şMß^ƒáğ"ÁùIƒù }K4Z
eøÿeƒ%ÔôëÆqÈÊÚ+…{öÓèIg»éçß—s"‹;#+¿Ûı#şÏuƒÃ;]ürçuy‹Ú;Ø·Í„|&ëæuYst$sW{?)ì¯”½7&µÍX/înÇ{ÛíZ)tĞoxY[*n[YC¨8ÿÚ¶ØOìnÂ‰¥‹úÃh+LüÏŒ³lgû‹|Dˆ7‹pUÆ…­İÓD‰´‘„«¹ÆNß^
u#9×téáÊ€@·²`HLWÎ/Ñ¬àfj d_w|Ñ‹BWàáGëJ T_'ú¶i+ñ‰ÁşNÀ?~£
–LæÇ÷-¼,‹J!Jaƒÿ„+\' Ïè¢|8RÚİßóì#\ˆD‰şuÜ%m½EÍ!ëÉOuÅh±+•…R!Ø®)6u,…±·`;‹Jz+ø¸yªíFsuy„”¢5Ó³À|ñ¿¥"ñ:€YQ!¦méÌÈd¿:ş¿6÷Ré})Á– ˆb¿m—ìÇ°ÎÓï	;
	|®v±Øë/(Nà ){Àaû±¼­N…	„"¦XjU' ü3ømmâuUÑ"2üğv+`ŸĞ!y>u€J cÏ< Hˆ¡o-t
\ “" 0VW$šn½üÁu0\‰PoP	fğÔ<taƒš~YÈ=DhÄAà³Ò©E04µ*±Íó3‰2Z$nF  ‹WµÁÜ\ âÀd Ìƒ[{¿Q >‰~ÿmbAƒN€!hm±AàÚq³£ÕÂê ÂOàCØúx?iÀZø0	(tÅ‚ö@­Ÿ¢ukxÀJû…ÁçÜX$bQ˜Ã‹ p(^qpn¥íà— pBúw<GwHò`ŞİñƒˆìôˆüÇ@üğßPÍBúHÇ€èøÛs¬³Hğ;ÊvÇ`¨nrƒ¶ @ëZE&‰¬™nïŠØØH/ƒdDB¼«QããŠFCŠÈÒzƒÁ·ºˆNCº	xº™ê*–µñ÷Ò!PeÃ´o±}?&Eÿ}×ã+Q„«éééSR©_„‚OüI;\šé‚+\9©@±{_öÃÓOú ~CÙ;óEüQ›Îğ?vNY6°l4ï_ã_Hs‚b’Sø—ì'ÏHñ+$ƒÁà!ºC§œôOR‰& Óöd— }TÀÎÔ ›%L8¿ÌU4ÛöÁÿOD1ÿú_±­m±¥ô ûÀË[?ÖJ@k´éÖ•€º;u\‹y¢ia_ÿ‹KIbi!fKÏØ'´Få¸eÏë% 86$ñOG„¹O}ÚÄNGUœD2ÕvZ2Bs†[»:‰Bø>samÜ‡sd:QŞ)YZ3İš£K6\3¤³Û½èƒş×^öëŠ…Š…Èü}¡!!;-uGƒş´21	kÎı÷%—Ğ!ykNüáÂv]yw‰q¶m/wr‰—•°uí{Xk2jKú\í3ÂX$­a%,,â?9a|É:ÏÎå!y
È$aN^ÕÁ…KüòÉÚ2?4RØÿ]Â¾êRœ„®  pÕHZH¬wY_r	Üo@)^EñÕ‹øÕĞ¶Àä(«Õûg¢‡¯¤[;ğuq0öšÈb£Ì[è¯¸ÌëG¡`¾…Öº5‹ O-3‡ú˜–
mìVFåê~"SÜÚÊF í¹.×ÿ7ıÂJ#ÑJE‰“¨¡]Ğıd|ã
WÄW°ÕëÃ;øs€ø
øBëjƒ€ÇGjøm$Ç´ëÜT'Šß“ÁWş«t`±°o1{Xıv2`¯{"rèuÀ£	B´uÜ GG‰X{¥©Qƒp5Šh‹IlGWƒìÿ,0·]0¾|gğ?³?¹¡Qëu9ÄLŞy¢	9W´ºƒÿÀ¼y
cÇ†;ü½\µüÿ–ë/ƒïB¯[á}²"O‹â	ØâÂt+yÛ&0#¿xA ZæBQúïbƒfğt­Y;¤-¸(İ¡\ïìº»]àöõÅÊ;Cvrö½D¬Êt7\¨P¨¶Æ1Vxæ»@…;“;òr[‰··à†©ÈfÄğ+Á+Â±xã^ÁøÍDú^VhÔU‰+ùNn®ÚÈ¯¶¥	!K8¨Ğ£"QÑ¼	
ñƒà¼ ¶ñAˆ°Y]^Â¡
›ìV³`o¸Á×Ÿ$~H_‹Ç+Æ~íƒèzvÂ;ùïEƒ ô#õ¿Í;Ë|9evS&xn¯Æ‚iBuƒÇt,ÁA°7rÈX*4&°ó§~*@c‰}ø„A½ß*Î;Ã|L\wæ"HN&KEüÙ…¶ŸR;;rÒEc7pì6;¶t
vo‰)¥H»…Ôxdi jĞƒe ¶¶QÌõ?mİ×ã)6hâèHó2Ë²}+÷ƒîßşæw;ê(6„Ûà0êötïÒX¸'ÑÖàÓPV¸^ˆ1Æ§ªH›qøV^üf*-Ñ¸ÒY~²·Œô€ˆôdP±ü!ÂM‡º˜›ÇAœT£f†‡R{µ~ÖBÄè6¶{!ê;Ès]ÿtëò­€ Gh_(4-ˆ?))]!x,‡e†=?³`4ÑŒ-ÒÛˆYTulÿ†£cu+ÓS!Ó)P Ãâ°¢yÉğ(àhĞºÕx-Ğ	X™ŒxÕp§Wü5¾ıÂˆ¥s)Fë	ƒaüşp!(vGqy|÷BÚt‰–Bo±Äìsp„Ûétø¦;X^€;kCFTq+?QsN”nÜA!ë¶9U´‚™ˆİ$ÃëŸóAíRŞ¦r½=£s~zè‚Û;ösv @Q^V¢ü·Q%C@­+ö¶«±	+«‰q˜qßo4¼1ˆÁë&Âs)EhríKÄ…4ïë®+ğë§7ğÀš“ÂAĞˆ„Rƒ¾·kÉ]x#Ø:0, ãVB
P#†¡A?¾u©À;W|ÇvğÆ7
­+ÈˆÇG#¼¹àë`seÒƒSğ,EÛwUĞ¢
@o[©u…ÂôuBŠ#€…FM;Éõ°…b+'+sTøÖ[138e@€<^Ü.Í·FCªcC+u)Ö‚Ô¨ÎdìHPs4ÿÖ$Ä¶<rô^(W‡ê~ÃD¾ğÉ+ŸT±Û4t# Úy¬Št4ŠPQ;Õ6£6Øc…ò°|ÄZ×jT794j×Û»#˜RHƒ<…l¼4ˆ_Ñcu>W+I³¯¨ƒT…P9^bã±YzËƒPY‘
ÿŠÍ.õĞ«ëzÕR_ÿáL^]ˆÙ``ÿWm Ä³Ïëât|,ëjq‹ÿ‚ş ›W÷ÁdŠ8 ?k›;uñ‹üš <úƒÁø€Ôİ4Aü&#©—Nú©ØÍëÒÁyÿñyşëuyıŸz°[ü_Šl©ôd?Gbî1€Ğèd ›lká/4r‘“d'j=X¨fÆG‚mÉJ‹ˆ!‹[HàE7 ÿ3ÿ§àx/ÜFW‰[S0]áADëÆÊÍÔ#ë" @!à‹ÁœÕ"ğ‰»¯X9}£¨à
{È¶®x· ¡ßT€È%û vÍH?}ÌÿÓDµís£4H< sETˆ˜.|
±¢@  7q`{¬ä;ß…œT?Ø R$>HLBuŞ‹êëÕÛ¥Ø¯‹ğ‰}Æä9	t${æ¨f5ajW“Í6WW¥˜uc£§ÄØ$t2´m[,K@>x²5²Ùí¥ÕÚ2:@GÂeÈB-„}6|¦àÂ³Ü³ºw±“3Û²}ßt´Vdä
4wÊgtœ³eÛ¬Ô&Œ(h”U´ÃÕä¿ğqa¨ĞÆqgˆ7xG¬VJ[-/8^‹ñI¦ó¥Í-Ğ^¦+ƒÂI“Î8@wÎ.‘é@Q3tÉI32$A&Ùˆ…cÃ	şı'QIü€ªx{„à8u'A|YZT±w9Z7 w¾À,WVã•Æz¢¨©(×¤Âqµ×9YİÛ(ô„vä–DéªPªöY°…#p])‹“Ÿˆ§é8ñ‹BÎ¿ErŒ3vÚQÀĞŞ¡zB’ôB;uˆ¿.^š^}%¿€ï},~ˆÅ¶ğw,è
È{ ŠX#ÆËnÔ»âe‹Âø½ÕŞ*–öDJG‚€e
pKˆİ]	X©…v+á	 	Æ$((t¡ûjbBÑ^àûÄ˜¥Ğ*\®<İ"W±Pè„èˆ–QüXÁy§ÂªX4ó=:‚ĞFTÆm&€8ì‡iëJ,4ÈçSeW`7¶B	¹®Ì'á	j“2õ›Xj‡ÛØÑ=jÚÜû°Pİ‹ËáAn
e›ì’Wµ±òÛW$ŠéÒ"EÅ¯­
ÇQ[À&"Hî;iºïÒ%*ÙËèéÖŠš¦òñê_"“éû ï™ÆEó°²mĞD^œÃjnäJÃt©[E©€1ş…`CôƒûN>t^‚òÖØ*¥FtTöß¾•§Lu7şcëE€~6u,¶Õâ4Â#=áEakDBÜßöö¶E'"òë",htl6wÒæŞJAñë;ûŠ"ˆöíşMMû€}Ä-jÍÔŒz	ÒD@°E¸u‰ã¼±¥hëcÜ@EÔéº]hµ}ã‡<SL<Câ¶Õ6¹€;ìûŞ.ğæwÉ3ƒÎIşn@Ät(jÃVÚcŠ{İu;D“#oëßÛB_¸~ìì9ñtP‰¶	ô†çæÚØîşoÃ^
à[(ˆcdí– kjg~8¹àZlipšWFÁ†œ»¦„l¶m»p‰md^—ìõ- ~‚İîf§é…4µ°½wuˆ
=´@[Q+ã}ˆmpñŠüW§È2µDİì×TD]©	<EÇ]œ~ÂtÈÂ!âßS®†ZÛ!S	wÙ·WäˆF]»8D0ufûØd(\" ˆ) ìFm|µ*˜ûeÑ²X°Ez8A†„}vÆe6,vÂÖ4…F&+€…¬c!«c¦Ôf“m¦—ÅöJïŸ.“¡2on›°fÛŸM	'Ì€&Ğ8RÁ Ù¾Ç¢>ÁBNHJyè2ä(Ä[÷Q–ÇƒV ÔÃµ·jÇ~¥êÁtÈÇ¶í6p[£Jb×ªêèÎ'HHAV€˜Ã-4¶9¶%³5ö¦|?+1ÓÃ³ë‰Ã)¼p½İŠea’G‰€?^cü°$§NÇxtÈ1"Ò·1vìuC-ñëÄv5û09EÛî‹€ûxt/X^QáÆt*òxÇ._	¿İ“o^^‡¤.Mü9¤ï…5j0[¡lá…^uxëÏ¢Õ¥ÀÄèÿí‚—“ P‚¿•6*Ã{Å]u	²]ôÿ…¶G*§_ŠUËŠ<]t_G<oü¨51=Šù6G:[ıRğÑsŠfŠÂŠÑ:"!x«
2PğòF‹ÊÂ[”œ/³ÁIüëÒãÔœBNuè2Òë´\ŠjAìĞ‹Áë›D4*Ø@>„MèuÔ!ØÖöÏì]ĞäGcBÃBfƒè«%¦^t_Â5Ø¿ÈSd¾]èÓ‡ÿ·âÁùİ¾Lœ3Ë…Ñt`Îƒ	lÛuRêIAäÖlà`È0÷ANĞx°MYÉİĞL6WrÂ*Æ+¬£Ô¨Ğà.ß»›iÔx†ˆë‚Á™Mj¯9¹‹<Œ[¤(Íå-V—ârÀ…‹h‚õRŒfƒ À€KÇYNÉu7éPRMîo hÍ\nœºñsãï=uO”!“tšhôŸ«á@Ğ%­T£X2T¹ÆY¯-Ÿ¼S~‰e@¬MSë]2ØÛÙu·8}SOF[Dk8v
2‰®Ù7Ø|_%7öv™UÜäïÂøHI1è¸şU€Ûìé Õ(÷R~Ü›ƒÑ Vä„‡ÊÜQ²3 ööt?pt:ÄvğÿÏ
lÁçë?<¿Ñçë8M. lt7S58ã%²Ù|ĞİˆÇ(´İh¡÷ß‹F²Ïx—êä ËÎ Yu)]E…Uø.Ô"Up¡,c{ëóaÕ8 ¶Øh­´8ë@Ğ±B¼p…6¨‘F+|œ”,¾
U§ßVŠ–·'È$>¨PÛvƒ}Ïub)U[[©¼T€önqÁ[õğhVö	lš5[0ÆÃëæ,©a'%ş;hÇÄQ‹Íb8Õj« ë+h!uV~çá ¸%´VZ{…(Âp¸ßæ/ïÑî89ÿJx¤è§è	‹
Ú
ÃRÙÑC@ÿ(JAÁÇ‹pÂ§ÎQWŸÿ"øÔàè ç<ªSš øÖqF^¨‚¯[ßv¨@úîvf(âÇ·„Ş•N¦è½—F‰F/}ÁV5ş$ïf©œ"mt¼w˜˜uËâ·ÿ¾UoæËYf÷'WtgÁ·í»°>+øH9XI…ÿU
‘É^Cë6´,ÕØÁ?ØùGÛMJ‹Y¬k¨@[ÀtPĞú%Zhö@ óR?ÑÏf:ÈI‹ŠMõ°j©h—_Ux+¸9›_¿N Ú jsEoëÔÊàÂ©ñA1H½ÈL ÄÉ /Ñ Šç„Û‰(¡GĞ;N¯PÉmª‰\º (5*b±B†	7p[9[İ+"ú |–Zª‰›¾3€¢U„	ê‘uU˜Á`DöT°íÄ\ğ*¾“§3À´P¯† íÈZnÓ#qĞY“ŠJˆ'¶åô;ê-± t‰ptÈbà~…¦BØ'[Fé€€¥JM¤	!”a«ŠuÏîm_F÷yØ'|¤‚ËåAĞëéB&3 È?k‘åøÀ:øìD@K7R­mmI»h»åR]lå€¼é­	:;ì°  1>A‰®¶4:4ãé3Ã­·Èı€‡!eÄ ïˆ&é6	Ô óÂg09%½-œ“~ĞÈ\Ç F5ĞRO˜‚WøgÉ8Ğ>`ûOøeÆ–ŸXB…2UŸ&g×à€6C®rp7Zl…ùÛ\Pî¡0uÁUÜˆš
ÚauøĞ¢¾$!@€h3T-D¢ĞınşÉu	ºtĞÇŞ×À¿tÁ‹ÖN*ğã»n	U8xæ	@@ëçÇF¡QÃéHTªŠÂ}ƒ‰ ¸µ0ónOÍ=R.ñ×x³€x‡4‰Ò‘[Ä~P´ıË~¨B5ğÓÆ€'„]èZvlG_ºè	tÅ^Q’yÄì;Ö"T:ÇE G+Àõ 
´¨ì½]Zªİù+…ĞÑè±`Îãhæ0°u»Æ¿vştÌLP‚ÚVËûg¥TôÏªë59Uø~êøD»Úø÷à~$X@
¾ÉP,Â°À*t.øŠY¼
iÖ=*XÀø“‡X¨´¸ÿœÔ°WˆàÁ¨ ÄGæûPQèö¡íW.‡ıìYˆ…öä?-ê€ÛÑ„GlX¨b-Â4i¢Çö¬è(ŒÔF@*Q£àŠ&Ÿ2/´° ı÷ĞPí'ã<+ÁÑø¸€ÉˆGÌ6¨mgtq8šŞñÜØ.Ö&$ã =õkØ €–¿\Š‚ˆ75ê0Q3ØÍ
ëëG!%a)â:	L5±RP¨Ğ ³ÅZèrA‰c¢êëOÁwq;
h»|t:n»ct¿A=t!@d)²ö…ÖÀ™ë%·Àëò ö{ölëà3Òtâ›.vğ|ùsüÒà÷\T‡ÚdİU‹ç{@lÑa,ë	9÷E=>AH9E&VèµØñàª+St¥SÀ³–	"f/À×÷ŠK¼ğ™‹úğWV ÁÙmìÌL‹€Ã0Z'õ6»û9}X]Ì¼ˆ×İRõ¯V+ıô•o" GÚ·ô€90Õhtü$@ ü€Ø¾0È ß^*aüöÃì&öÇŸİàSê-¦öÃ
+ë	t¥€(
 ¥Å2·_4°ZĞ¶Üğá¹ğÕjVú¸øHĞæ\üêò6hn„),ÅÛ10ô:A);\\Dxÿ±î.÷CG¼PC$Öô~2=;Àm1,Y8OÛ°ÛXğ »¬ÜwÆt¥PˆPÙ˜Ø6 (eYY‚¸‰ISM…¾øíjì!şÜMÓuÍŒ¬ÉLU„‹.!–4¶(´áI9æ«EKçhü&U¯Qõ«•SHøm‹¶­ªŸÔ V¬«jè™¼~!¸¶=ƒ> Â:ã! º®İHÃK81&<u¼å<2WFP];“6?'6â«€ÈêüƒF¢`­B¸cÙFĞG‹A5ü`$¢wfŸS\¾–9Ì¦v¾J¿(¶Şô3ö–*…Û3Vºõ;šrÒåtà9ttÕ0Bˆ.8NUrñ S”BˆCj@	Z><$mO6‘zì¿ 9uèó«ª‰]†ë|Á@•ƒî`¥í.D~ïŠ„ÒUPAÿàmq	;Â•”%™jë#	yî T>¬VO4RÏjU÷ª”ğA¬t,`	J‘i%WPü#®ú;Çw¨·€ïnŠ’ ØG@*õA8€€A¸Ôth¬rû±‹L•÷¾øP£¤A÷Ãƒ¶;äßjÚı}¥¥Y£Ñ¥ëR@yÿSGáĞg'}¡@=ÎÃïYlS8+‰5SëÛb€ÇºI« kÔjë ª\®.Å†ï¶ßl”³6°,sµÚpo@-\ş2ÖpßC \¨ÿªC½LÉıüuv€âØ²I-¤
lK¿†ƒè-0íŞ A¸ ¸,á{Wqû"3¾ÀåQÏ_¹FŒö"š‚V
":T|Ô
²à‚èŒˆ„MHU-½ŠÆrôDòÿ –à „îWkó[*ŞTS;Áw¹¼*âCåA¸  àó«À‹ÉªBBŠBÿAĞ5<DĞ_[ú‹5-öìzPV‰“m“±YÁ#ıVl	“V)#„€¯düh QKo£.\rMg´j@ò­[ïŠ”dˆk­sV¨h9 …Ş»[ëã€ TD[„ ¿ëI•r?¬Ñ¢‘²KÈ€Á ØÛÛØˆˆIarzwNvÃ¶méàLJ¾^nâÀXğjı¾ XIÕüÌÌŸZ NQpsKVU©ES=Ô'UOr9Uñ‹Òr`	ÇG½ÕÏÈ
uÏOƒï©ZŞZÕëı#U'u	¢NŠ¥
@ÓF¦3BŠG˜ª#û0ßt şs"%Iuóˆ[^©:UKeÚà 9ĞĞu0S•*ÚC,Øßíf
3ÒˆWÜÀ0{(W5¡ào÷“9>t&  'õft~ÆMP[¹o¤uij#(}ñªt{ªÕ¿l7sëVP8csmàj àKuxv'GIÕè¨?Ş f›ªÚÈu8µd>2ÑéÛl›$WšJ„á]½ƒM.4w@rP[Ğ„æ|¶·fı|©@»âci°Â„Kg~G®ı%Àb9~u]
½A pü¢ƒxl‹&´gÇrpl@ps'øTº ÆÔGÎnÃ<ƒüfaQù lA.‹Û	ÑlÍ	!È±.zğğ4åWî™v5Pv¶oP;ƒ‹0;|Q@ú;{w ØPÛ	C|K~d´h@Uû´;X(ôÎt5vXÁ3X27Åœ§jÚÃĞp!“á7Ğƒ@Å ÂXgØˆë5ì`zS4x0€ÅVÃ¸1p-¾¤ pl°•›&«â(ÑU`³<å,Ş{˜¾IŒì^XF›ıĞÕ?£¤"†ğ@›Vk0lƒ!6@VPìOK”8 Øº¾s€xø %ğøsO;>|C;`CÜ‰>GNãpDuƒÊHô¢5¡óìÛªÙ©ğj{Pnlÿb+ëèÆë©ÕÉ»)¶Y®LG¾J€xDèA,tPĞA¾â zÁ	ÁQ]ú;®ü"öögˆv£$‘D$›¼ °m7	¨	&ˆñòºÇØŒ"hêÀ4*s‰	}ß
x+9tU/~;wÛ§`Ú©Gnğü ¤h¶o¡ÌÔÏğjßÚ¢Yk&àÖlHTßíÜQ64ğZë¦‰sbR[	É¿´¦¨!Dº7ax€¶S@Bt<wŒ°8W>Ÿxb, ˆÒvuWçÿí‹íaBCs6nõXŞ>ÁF™u(@‰…ÿs(ÃÁÆH3‡H¿êWÈNÿ !8!.èöxÕB[˜­ÔÃ‚´¦P_Ø¼1¤-ªôäÇi¡‹}
}YxİAtæpõ"½{SŞ·âÃ³¿NS‰EÔ á@Ï¨-Ÿ "5Áä		lŸNÀ@+U²/U†ÒJ(û­-zÅ’Kœ›ÃÊ`xäƒà?ïZ*(ÇJ#õU•^±Á0!Hc5OPW«än‰	u7x7xp*à
2"£EXDs PïANæ0Q+xı÷	ˆ¾]ÅkA`Øøğ…|öîDØj xì÷¾Y&0<¤ÎŠ‹¨…ï8F [±ŒñQ¦£I÷RiÛ\¡C?+R‹]KáHÈsä Ò¢à">÷v&Á™ß®.]ÃT"´a·K|ÓÆÎë— UwHìu:WŒt~Sì›=3~œP_ëf›°“9tV5Hù¦+ Qû;ÄNg.`KDjPF"H‡°1NFë?ëÒ¤ d<MüÿW/@NÒƒÃŠ QQÂú‰gş©Q ÖŞÿptha"Ù­ 8%•Àf(øA¦‚½‹ÄÖ™´™Ò|.2PÕß¶‹Ê[^†ñ€¿SQÁ±è{UÍmUøãQÁVÓ‹KœŞÜİ].ëùü¹¸M‚!]YxXŠ{÷zM™ƒú„ÉQuÑ[}:jt¸Z›ìcÇè`öYt)¨uà'ˆUø\İ7¢‹ÛcUƒ›ÿ0‰ïç*ÚX¡OR_¡/ì±M~P$€ƒA¾#*R;}@Ç@Î ŠQÃ0OcÏd‹øxk?%GrdŠ&±Š-ˆë—Ø):+ıY|‹’€h'j`ée»aPntl-$² qØY(2!08@½2!DP=îXñ’±{ Êñ2Ÿ gĞè³>3ö‰uÇ9p`	î¬ˆ ;`Æë»9¤@2ÙÊÎ¾`8U=¼6)ä:À>šĞëUV UÄ·ßg—TÔ;°ò%‚¥ŠÿÇ.J„ıÿÿŠ'G8Ätò,A<É€á ÁA†à.ôÆ8wÒÀH¾´*®¤ŠxÁNH“c¿¸ÿåé> ¥g' U”Ø¾8ÃtªØ–ˆÚôØ”Ø.©â@²
°Ãœ>œ*®¥¨Ğ6l¸ğ;q"ñíß-KFwXrñƒùrù$wj1–Öø‚Ò¶[¬õ1ô^9Ãvl³í_rÊw,²-š*ğ8¬`¬Xş).İô…Pußë;Æ}bo©£›!C”é­ª
Ù”j!Sñ ı>Y
3É¸¾‹ŞŞú‰¡À ƒxÒyØ|êZˆ¸Ùºh‹ñŞş!ªÉs4µéˆnŠSÈÛƒ
ôØİ	 Aú,È|Ñ¹¨¦€ãAE°f»ĞiÕâ¹j±r_ï²ı¸w+VøÀ¶œä|xLbNDÀ>ø}ÙÂ"Q{,É)h{,hÑfy/­às8øT¢ÆiöD¿—WÑ¶§n#âHãÒ7FÄ Ã	Ô¬2U
J„‚]ÀØ9tÛt<bÇ>, jjK8ÛŞY;Ç+YÆ*³Ši©0
~KÕ$3ÿĞõ±BË]æóPŠ¤¡Õ[dˆ…›€†qWt­™šÅe‚˜“Ãkƒ¨½óuff•¢-Ú÷û"í¬*ë±.Šó¿ÂöF°Ò#bûÒ7÷ØY^<ˆ
İÚÑ©ÑSáõÕömÌ€ùÕ7f©à1«fßfİ~&WP;}ßVÀÅ‹€[Õˆö$ı‰“†ƒÄf ÜËÿ0Q‹_
GàBˆ[Ãê¨¯¬Åh­+cùÛã1.†Y9Ù~t¡»ÿ°"
í]œ_ö@Ü2…ÆÉœY°‹Há­hµÁ„øuÿx¥ğ¨…C_öÖ
q!tuØmì±øAÿ4°OîJF;sÍá|Œ± 9®H‡ÛY§tû¹Y=©>¦m×©‚#âäë‚H°•šÆ7#ÃOhW“p‹}·,€{ácÿÿA_$ï~N€«.$Ğ&|YUü™6Y3tõÜ?$üçÖéB¥ªVµâ}B°¯ W‚³Ê#ÂT	8Q›#†€Õnß1(şÉÈ$¶Š‰ìÁ@z8N–'Ç¿¦°ğ0
,ãğ«ı	C…šâ:O[hªgjƒÓÀbF ·¿'oˆj‹V°Â‚[V³Ny>6Pà‹ùÁéŸ<½ˆö3ÑÉ<I;Ú[cûhŠO_–‚^0€ÎyÏĞİ;~çu?âıp/TÅû‹HjjDŠÿ¥à$àBµ	´‡ñÈÿ»W
ƒbS!fÒ‹Ú*'‹ÓÚ¡¸õ¦‹k9GØhß ºø}‰£P•‹.Är`‹Êşÿ­ WĞÉ‰]ğ|m…Ûrif÷G·BPë
+™+jÃBk†&iÑ3¬¹U-\$‹WYÈ³pë»…²OÁt,‹Ş{Áû4ZAyÅ°@-é+ãÈö¸X9
u"Aëñ0ÙÒğ€uÇÊ?ÂY ª·}]ôø¤¶±Ôöƒ×.Uá‹FS!sr5ñ®+Âïn¹bİHGWø40ÛVÔç·|b@Pª{ÿuøé6
¢¬ğ^U ØdÅ½°uÈØ€8@Ù¾oG ë73ô9Zƒ¹>9#ÿ`bô¶ GÎ½ïG‡pDÆmlkT)0È Ç †µo0!ÂÏ\«Üêo­¢ø`ãat% Õ<rÅG]EuA@¹·ë÷Fo¼ƒÎ¹	~áÖ%ZÎÌŠGı:ÃªŠDU;Óo7\D¨k—T>`ƒèmåí+tEt6HLUÔD«9]üØvŠôƒÉâ·hoÈë¢×@u}	,ìZ˜YusÏş«ü6 oÎVë‚mÃ7!ÈuYÈ†­e.`{«bÄHt.ÁÅ^háu@À2€Í@%jR½cøu.âÌ¢™ì¿ÿÿÎ-Â.æŸ:tøê	mA€h¤î†øğ3Õş‹ÈÅË}ÑC[Taªi¡±´0p XÛŠX»DØˆ`XYoÿ¢	~z YĞ0 ×‚@‹ 7‚y³‚‚}±š	PJÄëçE€pü<±ëDj8i²šÁ¶˜2V:Y›$§H­†íl<'û8Oğm[Qy_Ä_¿šÀ °«Y¢A,êoTß–~Bô<‹:.1PĞàí&:a%
ä¥[¸§:A·­P`¨Põ·– ÑuÒÃÆMÑà‡¢µCÉz¶n÷ŠBDéA0à¨ë¾Ö¨3[ÒÊ[¨æÉŠë]sm¡r |=ŸÙá†èYYGİ…`İ×¥ËXXğİöÜ<0ÜnÛÜMm]èèÜ}`ê·Æßàv„9b<ÕÅ„"E=ghS-	°'²Pªì@¯’Î¾h‰K|ëvøe,0 ¶åå@1PQj÷`åuÔŠ}0Z‚¶¿P¥ŠˆŠÁFbÇ¨–Èó6ŠAÑ»(:ÊtöAën@ôM*¹,0ÁeÁùEk£í†H40tú8uİŠmÅ@›ˆ
öMİ ´„hÆêr@Š¦ªúËt hç“­İÅkYnD dHÁ–İò…¢–Éd(å* PO9 vo­aLeİ$)Ş–xˆ5NP
>"ï-ş¾À[D®öŸĞÊQ‰~…û'$¾Vfh±XzÄ0ä4ñFS8]Û Ô.£$ıû}—_ŸÀP>OÇ˜³úYYÖEÃ˜-ƒâ»%~ŠPˆF"8aÁhˆç¿oíÁMÏ˜:ƒÈM„{Lh±Ğm.ÖÈDÆöošAEÊ^Ky÷Û	jğÂ-°ûd|tGmk|™^÷şh	™ÚRBFº

Ì0Ò"]"Eô—Ğw!>ä !²oS…d,V;±‹€B‰•ò·nŠ¢±3ÉC#¾}ÚËõÆ 0€Şà.Íû{0U/"Äğ6.0YGûÄFÖH~Dğ /YˆTvGÖè±[}+d÷Ş]ì¡Û9‘|­-€İj0µîa1nûSVıW{¡$;vpÿùQxß‹ø)SK é4qÄ.;ğœè£:n}øü|&¾}"ô
É!lşuù Gş#]S#Ò1k¨¡QSRÀ”5}u·ä–021,fu]<àÉ²¨]û`A¢ÃL³ëNÔÜ!°÷j(Q?‹jû‘~Œ@qS¼²÷
f^_›„P/äL‘ 6PËtGK ¦úoCùƒúr-÷Ù´‰¢Q uú|ŠŸ$ŞhÛÂ&âé&ó«2£ /#JƒN§8İmH[ˆ D°h€k"pÔ¡¿@ğ£¢¬Ï­àĞê}T†)º€ßoúÂÿÉÆF
¡'ƒÀ ÕïÆ$ëŞª@Çdf0ê<º¢ñWqÆ*Åü
8ä;+¾ÿªW;ø|Æ9=g}(–m õ¬ä“±Í!ƒƒÏˆ‚YºÆë§‚`{ƒF¥@o“$Á€+ğYŠM|·ëB	‚(5LMıÑ¿ê8Š³2`$
.uÃÀh§#xŞê*(â`	¿Šˆ¿wZFC;÷|‚?û´3Û‹#Ûƒ<ÿ²[¡ÑuMp/uğ	ÇèööXë’ÃH÷ÀõÖÔR«d2ÿeWpu,HÌ‰>-`)CBTNØ»»ˆ

ë€CŒà[/|—ÿDt§êaX&àÛ"´7Wxö! ı{İ	·…D ƒÇ–Ä¤êöƒÃ$ râäCªÑç%	âD¸XV‹}ªW¶˜dà©hv/Q¨An©İ<=t…Ïh8°YOÎG0ˆ·èù½ ÷;˜Uôc;ó1©˜(ƒøíàA{A8t9U±Û‹èYE€?I"U4b—Z5Ó.W–h\šYı7²]—âEñûÍ¢‰ÄĞ~‰®ËT²`ôü[½0ù‚Ø ªdæVA<ÖEpd¢¨‹ş5Håh¨tUS[¤ª@·æØˆÒİ€aÔ/;ó¡†İ=l/‹E$†PV5nk"³HZœ£ÈEÄ¬Œ<¿µDTÜt!Ğ¤¨S+	V-ÔhŸ
$7•ßÁ;¢ó8"uD|@€ú"t¢1‚)µÒ]KèÆ%ÿë;2öˆF@ÕëÎ±o¡$ËF@F>C¼m±@DÚöƒs×Š¶)e t	êFÑ[		uÌ“H¿1VœJfƒe €¯9"0îgD+@ÌD¼'ğRÔÀ¶¼wq;ÅöÿÇĞû"\u_¡ ÈP’,°UjRÒ‹}¡jß¢mxx"ã*ñÕFtÂëš8Òâ°¤Ñë‹ÓKhÿ:‚ÑCˆÆ\F±E¸Vªë€§ó
hî”Š?×„#‘­.(&Xâ©æ„@Êî¾‡ğ¸Ò0ÿ Çƒ'a î¡úˆ¸£I%«hS 4U
rwöx©ÚnÚÃuÕgót1ˆö{!î(X€ Ñøş$ f1‰§ãÕGğºwîâCuğl?„yf9³Û­jë@@–mãêò+ÆŞäÑø@€FÕ]ÍI­K%n5;ë¬2µ¶jâ#UW*škå%!xšT9æ'\Tc]¡V\,»}mšSbL¥u±%Z”
V`u¤íß¥@
uûö+Ç@jUÎ'øD´EU¡4ÖÁ¼\èW=`E¤Šƒ–][8ÅƒLRk*!hüRÅ‡#†»PlYZâ‘vÿrì¤öEŠ×z;×¹¦€œADõrğŞj‰ñ#ñ™;–Ô°U¢l‚¹ˆuŞpØŞ‘şúuñ+â«\ç‚]Ve³³ŞxPmYsìí$^½“Ye<v)ÎºŞ'»øƒj° ¾şAt<ì;Tq“XUvÙØÙW\TıÙ¬{ÿ¶Ó”h  è†~—',ß±¿_ë&ÀG±-=0÷6*f°›‚jôWX<ÿ^ÉŸĞ¨C4WNğ6UI‹Ñ+;şv	¨´@"‚#.!Q|@r)ó¥ü¢	^`½ÇºCé´•Ø]äÈìfˆè¼Àá¸Ww]÷<Ğü #Ñ­ÔFQ²ˆDÆ–­¹VYÆÇ\ÌÉZQIO+Iåc%ƒÆ¦ûº³#F!GŒ?4MÓuŸŒ„|t7¨LÓldİä¦iºKäèèìš¦išìğğôôøn¦iøüË  w[gø	ÿğd iºÀÌà²f{]ùTÁ3,1Ì0
+31’A8@B$ĞÒØöıãüw¿ÿÁ¡Õe¾ğKùĞbON+¾H!,¶¦Û‡¾Xx m:¯»oNXOV¶—°]B°£îïØ€}Œ)'$¯@\‚«îÁ®İÂEZ£[ôMÓíp¿]ÓĞ4$7g4MÓ4Ó4MÓtŸGB—¿@¿PX\KBÓh|‹·µÂ’‡FÅ#r-!ÙÙ· ‹"˜+£®0¡¨¨êıW8"f}Šw€RÕ—	B‚qPîKU™Ğ€û-5m¥.IMâ+T«p®
X×‰ØŒ‰ôÄ€Ñ•€ö$f™Yà£ ­(.0
Ø øq2Axj<Xİ–>c‰é9u mÛ(uu¬ÂñJ@ckÀ\)¿‰`æ(i¼@]u~ùJQ‰õŞ,ÈÖ¶0pëW*D®àvf‹+#ÇÅV‚gÂà:37¹FÔUu6qí1ìouuôruƒ¢Á¡Ûé*ĞVA¥ñÆD‹<P˜N¦Ø0À}	ÖFo¦™ıÛtŒúı!€ëK¨ÍuÀ¥–ºu3àL‚â&L€w	Ûİš’9Kv'öE†sÄBg"m:pàîëW$öT\ƒ(%ZÑÜ¦C‰*G®+25cøªĞ²¬‰83À`Q„g/ü¡*?ã&‹Ùa÷,Á WÙ?ş]œ¦XÿÎ,ª/ü:Gÿw‡II÷Ñv«	,ŸOÇz@ŸÂ`ÃXM×}ŸÂÜpœÀ-Ÿ\Ÿm %ŸMÓu±??,$]Ó4‹üŸİk	ÿğ`$Ğ4l€Ÿ;…œäÄàÄÃè!@×m8à‡Ãø@-Ÿ)Ÿ.6
yÄà[š¦iº”œ¤¬´¼#@ iÄ×Ÿºëğ÷Å…” Ÿ±ÕV– ßE Î-K:B«.U•ê$ëóÖÊA¸üèF£sò`Z-Ä!?ÌJOÆ:
óÏ;ĞŠ@É!`8RÁwï·l‡j]íëRúÁ^ú7ƒ¶ÑöDV€^eş -ğ­ˆEıjú	ˆµıƒèM
4QPF(f@šï“¢õÖkSE
#ˆ»"H	DpDï(ĞËª‘øÆˆ’T…Û VEm¸]Í!ØÓt&ì”à½Uñç3À(‰¦^ËÚ6 
)áÀÃ è §á(ÁƒcQïN¡.¡¸İÌ~*9e|/
VAğSt•ÁÂVj	Ç‚PğçH,u“êw)r8¯u“P4ö€*ëƒ3À>À¦lSÉj?ª‡ÁØëÉ¤j
q°~Å¶Aõ˜$BGí€.ñULäÈùu	w[€ú#÷á S‹ØúÙ¨Q	áÓ[í/„P€ù@sD¿mÿ s¥ÂÓàcĞ£€áÓâSÔ ã±À¤âûÿV(ºêhº+2¨u.ƒ~ Ğ?¬†‹;Fæ	)ê>î@‰ÊmAûÿœ6v¿¯ÃˆHÿFïŒU} æ‹Ã/áSÈ€7@%_¢&"È d‚®?¹¢öu)ë-¾GœÕ?ˆ4ç8àŒ‚À0)›¬(Gê‡ëËn†`Î:©‹Æ¨dE¹Ë€dı­È²×É†* 1¢¦­D£êNˆ³*¶ğòËà% †‘–E —0ïP˜Tlm²L-h	¯ÆÅˆ`¡€ÆÁo.áâRdå† ‘Cˆ=´QÅé#)[»õÂ
úù
t	ğı^*øÆ @ˆ§È•)+ÊÌîŠpgÌ‹ø;j ª¿"ÀÛ³WPqÿ40ı)bƒcCşy©½ªxÇ| dV@,½­ŠÙ‹)Tè?tbj^+¢à#¡„‰0ë·è‰ìëÇ<ë–ëWQNdZôÑ.k¨‰)§,œ0à,G ¢›ŠÌ’€8ä‹-VWn«hŸ8ëÒ+ô8V"^ş„ˆ5*:hñÀõŠQÿIX7h‡ê_sA"Ú¡sf	ƒFjßŒì;(GGèr°Šàdgˆ26Š3ƒÀà@ROp£RuË×ÛÛY2%NA¤¨®U©VhË-´K…fÈ¯w9KİX­FD¡X`Z±ˆQq¶ Š Ñ¢Ó‹Š†ËŞämJb!• VÂÁpj=¢fD> ÛözD>¼Âë÷¢¨P>F¾.Œ(>Èì‘YÎˆê;İ¶İh‘+DT5¨0|EÌˆºÚ L1Âˆú»%8hˆ¼ùÔ[üUP>§^VôFxA‡u ßhì½íÆë@©¥SÀ Ø“_€#ŞX£C½g‚‚#ÜINWØ4áP3²Íø ¾€§p”+ fÛ°jS,pYj˜ŠfDÑêc†n¾<ĞĞŞHH²êuÜWÌC(‚DÛÍ†Âw6²gKU)V_jUv]R¤PVí«øeÌwg@#ß¼8ƒ<ÑPhxEØ+BÎ¦_‹Ğ	AS^ÂıD  „Îkğ¬=>/ØÃFÃVƒÊxı)`V‡9öÑYJ™à¿îS°j
Áj›héáuY"ÀS¨Az¥5úÆn#]V"÷ k59EAÌ€ /[€7ØA	C0|)1ƒ8²]DƒˆSƒ$ ğØG;[|¥^`ÃÛ-*­UV„;Ğ	ÁƒÏ)Û	Å¯ Ïè 1`TSæ™Êt17(tGuD—#rà‚œäKÌ÷öx=X™^Méì7L8thaE …RR¥*/Á·+j$Y™yø|oúh„m)ƒ± ƒÅûÅßëCı®¤Œ^ÈN¾>ğä0êVù>	€7Vg_†¤zÂs Öëä‘¸ÄÁãWûW†h	UÄ¬ƒ¥ßVV;SWsÄÁß†Áàä<ÁŞ¨&ĞÀƒ<t6ZhU¼ ¥Ø~£_"9ItISjôë6ÆÖÚõüöÜ6H¼ñ‰0Œ[ëm_D
¹°^{Xj¨XˆCY72vNZ+È~Pv{ÉóPPƒ²3À~'ƒAs(U,VOëÂb	 0F‹@İ:¶ŸÅ4Œ<ÀÁç¼÷‹)0I#íA l8L³h°re˜$h |{Ï[;Ã~sr‹ÃBD'2Ã|9ÛblHSY)EiFÕ<ÕuÕğGöUÿ`¿Ğk‚6LY‹ÆëdBèaT[¨Ja—Ğ+/S© ¡ ¿Ó;ò¯¡'âò[²1€LÄ_7•¡0Dk½#C-FPYdœF¥¢4ø¿ˆNlw0ºÿuø;Ç7uûŠ?õ…À‰¥$‹×ë!*JEÖÎĞTLØÕıè†Z¡;‰J_^É!ÊÔ%W † ««†£ÂU}s¢–ˆ*¶qÁhÆ*ô‚F@‘bC2Í7ˆú…Y]  õHtŠ#vÿâwˆâS¸6£VãÆ<UÃ¨ƒÆRQ< +ÂÑ„9$0ŒFÀh/Ñ¡YÛm‘$ış¢hu0´ÕñJAµŒUÆ¦:6zŠ~¨ĞF]Õ«…u;îQ|£Æt$ûˆTA-¢ÁE÷Â=Á[0ƒË/Š <ov8®<u´CÿE¢»¿ZÎIìs!@û·.ï-ë^ÆCD)jn¶ësÊ+aÀ^ÓÏ
ô:*A†váÔŠ- Ğø»F‹CˆD18lÛ/‹u€}ÿc
¿€ÔKWjÿít.€{w¿’‚în¡j\tó¨@äU¼âöˆ+F‰]Y•¨ep°E£Ø ´VF3A±šäÊ&ÚVAaè:Æ‹ä
âÖÈyeÿ8† CV|U»no®öÅT9Ä¬ÁJ¼ÆMÿ€g;^#°pÛ¢hmÄHï¤³vHPáÀŠf(Åä@ë"¯9òÖÅ&ó0t
[-¶Ptã IOT/R¶€€¢]¾Ÿ(vğ}s#Ê;Èsõ[ğ1t*&;Ït3h¨ ·tMì+IE0æRCL†uİ¥Âë’uë>&/ÅBPÓ #Ï8cI8K9ØÈRu;¤7Á; ¾€9ÇtË©x÷¦(SñÑ#M~§7<Ú
fÅ+ööbÛ·ÄŞ÷¨ßêÆ¶%M¨hÏ¶AåZ|Ø%¦X) vàQC?µø•1+VË8ØPguWÌèk1×2¿Ñ,œĞèuštØÁVˆÕ¤
	¿(Õ¯>Ğyh]Ğ­Y9Š*QT¾	C QdÏÄánÓÿHˆ=uxêtsöF ~tmj¾Š ëu¾Ò¯ƒ6AƒïK§‡‚ív·ëXB Æ b¹ïyï£¢°¯ğìGèã4YƒÇj j Ztµ°9E`sx›Z¸õ€‹‡-‹ó«HV„  WüŞA€aiÙšğ³À÷Öµqğ¥#	ğVª+K7;YÙmîÆAŠB¼DĞ ÷ÿP¯ÀÒ~ØÍYYà%UAğ[6W‰jX9Z¡<ÏHÁ,Í² ëÊÔA£I©ÑÂ…·Ë×ÑD‚Æø£¿‰#Ö½˜B¹R‘È9*pãÒ;ÖÑÇZÅ©ÛouÌ#Ï^Ù;Í¸ ô]EqLÖb¿r]öÇ[Y;‘V°d®êz¸‰—j&‚n89ö÷¶[ÃŒ¡‹Ëº‡Ê¾*}¬/t;Îà% ¶È|Pû¶Á…,†s»V	.t&'4½KÀ_ëO"t­‚Š$0€áF7Ê™YMjaMü:*›XY+ÊÓÁ¥ªÿâ÷Ò…°uF1}»u±ƒ°ÉÀ
VP·|ò»^_Ş¡f* JÖNî¶aÆL‹³Y)LnQÓà˜7–pİQ¸7NxÙWj…€€Ò‰ U,éç[–ª•ÜYÇ\»ûƒÍ_fÃ^™³È¢
‹ìĞÛw\ÚM_ˆ+òZ‹Î»… À]!CS]Ú3bÇ`?LW|8üY7"Ş.ÌÊÿ+ü! AO@/|t+AmşÈyÙó«8)‚'á á[[.^0‰4J†ÑŠf~ÎÃ‡^¥o3É%f“çAùæº‹™Š,ÎÁ}[	ThO™¨Ì®E‚‚×ğ]}‹-æ+Ú÷ÖŞEwóÈ#ÎôèEF­Eö‡ªjTáÇMÕ-5häŞ¨vÃ[€¶Lo5jYã‡|­‚¯•Áú¨5áˆÆšgOK¹ËàBãyà.±PPÃŒétÙMeÀ/HÚ¦‚—H
IOu*êë> ñ¶àûÀ¯B&~âu&äµE„ ÃÛ…^†ª…Ï ¥yô>TĞİ[’Eèu°[Å‘w!ÚÒGŒPúìG¤ÙRi+OJğ>ë<;Ø6!`ƒ?†‹ÍØXw^V	 JB‚Mv@# [Ñ’ı/[Ü’;|(R0Øñd/aM÷€&‹wr7»åÜW¡+”à÷!‡ öó#bÀT¸5“¬w0‹‚ÜûñÅZPŒuŠ
ŸT„q‰nT\ñ u@1kÜ‚…}È)ò xÜ-ÎàˆE3R$J?¬%üÄÔåúÿun ÄaîİÇ$ÉACÀ,¯m  XœJÜ`‹/S~Æ·Û¡~´(7BÀ70#×¾ÒAO…»-ô0Zˆÿíué6ú·ø'|€95|Üêb9½ ¨õş>ŒâB·1ÿBwWvV1ŒâWt{cf©X~Ş°Ğ6ˆòòØ:PŠİ?uü¥¥f¥êË¨hP»Q}·È°İKÚ‰Œ¿Õ]üŞŠEÜP`{L#~
Ñƒ%dŠ.qOº¿ÆÇ†‹nF%ÆÁèÑ@‡½Bup¸±XÇÙ¾­ ÀÆ½ôˆF…¹#J=o¿à¤SµÀMÓ¼»=D1f2ëK&[©fÊV€ºÿìÈ 6LP—9âDÂÎ[mTŠ1jÉqÁUü.õëÙä‰?ÇÀmÇ_‚Ëëß
ÏLH"1wöÄ„«wuBÄHt÷ÌğØGgjëÛP÷ tÀ´Ög¾Û‰§)tP°âµïœWämŠúTÑÿĞ{t€·#èœSí‹Í`M:—ëøH–æH2azè‚R à©şñ0aŒ¯z¦@›¾ = ‘ár_Õj\m( 2Y rBĞ/kĞ‚Ì-j§B.0ü
&0ˆBĞ4©„ Ğ5.	QÃšŠ
·
øHW¹Ğ((Qô!şèÛŠh Ä<	*ÅR¥şHìwÃƒBâò‹E¬¿ëBÆg 	Š°qz(ÁÄ 6.vmFQƒ¨ù×\ó{ƒ¸ë)«ü«ÃA2 $P…
Œƒ£“Ã„Š_½„Ô†<{]f3Ã¶Qp9Ãt§F˜lŠÀ€Jµ¨±mí]İ+VT‰ÙNTuI*ÈğXÇFXßî~êf™u7;}¸¡¼Áª%
¥Ç¥€I·ª& àƒd½=Úáü !/"Aúq‹Ká|â‡‰jĞ°H°T[4æXiÓÚ=Õ×ÓY­k½†  ø%‡Áª¹h-‡ø'ø^A–†Ä@ÄFÑ9rÂ¨Òö»í<Iº…;Çs9pAô—HÕØ·Š;ÁtB±€^xƒa—3jV[8Ë¢>Ãè[ö Dò8¦"FÅÑ¢>Uu»kÁWFS¡´,= á4í^Fµ±½í7û›}_ÏfÑPpÉ†JR Ké ğ¬?8€‹\,18kÉ¾¡P)H}Fj ˜EZ0µ~ıöƒ áğ÷ŞöN;óuT°ª€J|Œú&6EüwÕ laë¥Ãa¡ÑÂXÀ@2ja
x´Qrs·ALª;=^‰
Ë4„^p… 7ÿ6ÊQ«ÈíÓ5bÀ.¢ÿ0¯`àËÀ¤XÒc ^ÚEş‹xßÎöj·Ú4?ˆhñ
(lˆ]×‰Ë’ø%°ÑáÊ_`-´Ô¶¢¢H.gù»+t+é@Áçˆ[+°Ñê‹*‰lD‡N’3;6mAVGN@ß‰›‚~SÙSvQWpó(ö³Á}ğ¡SäØSÄš} 	zUƒ¹°Ì· „#‚yM¶ªÕnc_9U"ÚA+"c‰CôB¡¡ÁîJíPk™Ñ\€ïÁ|ë€s~ëÓ¾¤sÓÍZ·eÿ÷!Š@ÙÃ£:¬mf?
„\Ë†LmÁ¡¤	–CÅĞÀZ·è\£¢CÅğe—¢hQ3Ú Ú§äÇì‰.êZ¹€2ø	_€ZUö1•ùuï*üGëçj^i	‡woÿ	»êŸ€û1|9¤ »Z2Lè’:$?T<[Ê¾+tÅEÔ·P:`ç-<ZØÍíÈ@ôXë§ëÛ]ŠÂN7|Q~®Jƒˆ1°Íâqt1àt,¨¶íï·RC‰E~c{c»Fe.rj}Oæ¾Øj¥	S%È@tØÿÿWYOí:¢!ÍµëÂ)æQ*øÕ}9 ,~zÃV7ª tKë‰( w¢»šÕ´sP´Ğü€>°a£*)Öø)ë·r¯Øƒ
g‹ÆZá^hP9šûr6æ 'óp¾æ2DsF¹+"’Ó-#EQŸæ s³Û]u`Q"™ƒ8¡q¸
Oş‹¢‹DEšAÇtdV@p²œëeä°„û¾Ì‚ö2·òD\ˆ®ké´ÂK<{!i	XO	ê/0DëÁ¡*hïÜæYlÿ\ltHHZÛô"RóX.Û%N
O×ø
¤•ÎÑb@ùŞ"zVĞérG3öŸ*¦@Ê¦¶=¶ı”ĞşP<Â¾¾Qæ€ÀqI4p,7ĞOù>á¶m}ğ>8£ÙX9§‘úv€}»|şE»ªƒ‚HsEÊQÄ†ÀÔEjB {ò¤t±²À}¤º@ğªh3ˆ‹hPpM([UÚZ#	à”ø}Ù
Ü+E=Á~0êÔAªU]5Daª`£GÏ¸ÓõşS”¢rğA^=°ëÿÿW}»(WĞÇKĞ=|uï’ÀuÕ²lHÂÆÊ•µ#‚ 8{ö`x
7l«	HTû ó_ tîâZq‰Y®€­™Aªì¡.İŠ÷æh·ç4Í¥i8“è
@ŠV4MÓiîØ¤Ì€|uE®]È¾á7ğ‚ ·f…ÉWÆ"¦išmÌåæçèÓtŠéëìXP—Míîûï?ôp¿E1Ğ‹C-ëÀÂ·%‚KÒu…—Ûû[Ãufƒ# 0.ÄlíUfßz¸IfÇÀ‘€·D;øu?÷½£VC:@ú4!ëF.ÈÜ–tA@^	.H±Õë2#ËC]Š_#gcÂ~ïÓh- ü€w°Ğjá¼“Ï!éi ±íêÀMÙî¦et_z©NfúkÉMÇvÿö´í¼)Áş	ßò¿Æ9P'øD…Ü}úÿ?rcãa; äFí¿ãöV‹îÖmG3tf}5~¶ª#·—}]ğÛ û_…uúîşÁêZ|úßh;éE–ÉYuñn±¶gæ~w‰ö`åN?OÙ
n… C8~PQ(q+ÿu`Iâ+R$¥ONVbØ:ŠEd*j…6dÓ‚¶T0E™7s´-ÑŠNQ‹	Š—¸A«aG·5T|0Ò= ü¥7ôHëñî@fÿ *Ã,ˆQß(S€djnÿ/M%ŒfÛ ,ôÙñ Ä€c9ÓH,©bWI™¨ûA¨›TŠLÁ/`è+X9*!€áÂ ÁV.‘Æ»€ïOu€É€&ˆ
f% P‹àÆ%‚“™$ŸŞAâÄÒK
]W¤¹şO—¶äFRùº
3ø#Â#ÊçÕª¸ıƒf=GFà.TG¸¶Ä#Û
­úı¿¿¢
ëz¿?pí:fÄ»è*ZDò•…V
•[ñ†Æg9Ìøï¹3À
—¯´ôÈS9¹Ç hÜ¿9ÕM‰6ºX|$Úâ—üª« àğÀ¤Z z¾I’KG è	hè µFÄ,O…>µ£ÁäüQéP†¡1ş‘¸ğ%r)îƒmôŞèu[´VmM›·¿Xœ‘ÀƒM~%!´¹ç€ÄÎEâ9ğcÁ}1¿Enµ†èØ=Ü,Á:“ì@¨KêV¢C@XUAûR¦/Ş€w‹-P[h8B=F5v©± Ş‚Ş 	[éâÿÒ	â\}ôv[hØ0fæ(ëÉL_l·ëâŞ–ˆ¥·¦sfúŞÇnmÄ.–N§NhToØë÷ßüm£ËĞ™Öl€1 /TR‰~kb7^S»p ë`ím ½c}½ 0Ök! ¾*¾!AZqŒÃT×t)b÷a'R%¨Ú<(I–¥Æƒıˆô¥cû¬nCuôà3É>ED uçĞ²ª_°?»(Â ˜ÌŒ*`G×Ã¸Ô`NœƒK¯«±%EÄüÃ2+¨ª²‘ˆ  dTUÉC	 !İËn7ªí«½‰¢t.¢.zDALé:P @0»P˜¦$OèÓ4] šï5M?Wkš¦i?wìÙ`HP¶éº³ TsRW@g°op06X×mÀP
`Ø`ƒ   `ƒ2€@à?ƒ6ØXƒÖuS;x8ƒuİ`ĞQh6Ø`(°6Ø ƒˆHğİ`ƒTUØ`ƒuã+t`ƒ64?È‚6Ød$¨l°Ã_„DèØ`‡Ÿ\l°î0˜‡S|l<ØŸ°Ál,¸ÁlŒLl°øRlÁÿ£#rl°Á2?Äÿ°Áb"¤Ál‚Bl°äZl°Á”ÿCzl°Á:Ôÿ°Áj*´`ƒFŸŠJƒ6ØôVÁ¾`tÀ·ÿ3l°v6?Ìÿl°Áf&¬6Øaß†FØ`ƒì	^`ƒ6œÿcƒ6Ø~>Üÿ6Ø`n.¼6È`ƒN6$üÿQ`CÒ ÿƒÿqØ4Ø1?Âÿa`ƒ6!¢$6ÈAâÿIƒ6Y’ÿÒ`ƒy9Òÿ6Ø`Ci)²l°C_‰IlHòÿU`ÒÇ·ÿuØ4Ø5?Êÿe0‚6%ª_’ì…Eêÿ¤Á]šÿi°Á†}=Úÿl°!m-ºØ`‡ŸMá°4úÿgÿ’Ãÿs3a„¤Á?Æÿ§6Ø#¦_ƒ!i°ÁCæÿHl°[–ÿ’l{;Öÿ°Ák+¶ƒvXŸ‹KöHƒIÿWÿ4Ø`Cw7?Î6Øÿg'®l°Ã‚ß‡GlHîÿ_’ÿ?Á†¤ÁŞÿol°/¾Ÿ•Ä`ƒOşÿP2”Á¡%CÉá‘ÑÉP2”±ñ%CÉPÉ©P2”é™%CÉÙ¹ù2”•Å¥%CÉPå•P2”ÕµCÉPÉõÍ­2”%í%CÉPİ½”•ıÃCÉP2£ã“2”%Ó³ÉPÉPóË”%C«ëCÉP2›Û»•%ûÇÉP2”§ç”%C—×PÉP2·÷%CÉÏ¯ïÉP2”Ÿßô%C¿ÿ;éãŸWïO[Mï„îßY(U³ï¤¿]@?ßX÷Ô}¯ÿ\ Ÿ¬ŞYßZcd°³ïÀ`?CN6Ø“a?'‡œ10ÈÉ!Á /¶,Îï¦o³ìÙ³_—
 ÿ g ïCøŞS·>cöÿöÿ inflate 1.3 CopyrightÎş¿û995-8 Mark Adler Gl°Á_w{
süxÁ[£ç ñ8=&ï#gë§ÓéŸ'Ã/¿“>«o£ Çã¯ 2dS` C2$Cl°/$ pCì†+së	Oø! ËA óğã1á »§+Løñ˜ó—ãğãñx·;@Ù›ëãÓÃ!„#Ÿ£†0„§«¯°Áã#Ëkÿÿÿ–0w,aîºQ	™Ämôjp5¥cé£•d2ˆÿÿÿÿÛ¤¸ÜyéÕàˆÙÒ—+L¶	½|±~-¸ç‘¿dÿÿÿÿ·ò °jHq¹óŞA¾„}ÔÚëäİmQµÔôÇ…ÓƒV˜ÿÿÿÿlÀ¨kdzùbıìÉeŠO\Ùlcc=úõÈ ÿÿÿÿn;^iLäA`Õrqg¢Ñä<GÔKı…Òkµ
¥ú¨ÿÿÿÿµ5l˜²BÖÉ»Û@ù¼¬ãlØ2u\ßEÏÖÜY=Ñ«¬0ÿÿ¿ĞÙ&sŞQ€Q×ÈaĞ¿µô´!#Ä³V™•ºÿÿÿÿÏ¥½¸¸(ˆ_²ÙÆ$é±‡|o/LhX«aÿÿÿÿÁ=-f¶AÜvqÛ¼ Ò˜*Õï‰…±qµ¶¥ä¿ÿ¿ÑÿŸ3Ô¸è¢Éx4Œ¨	–˜á»jÿÿÿ-=m—ld‘\cæôQkkbalØ0e…»ÿÿÿÿbòí•l{¥Áô‚WÄõÆÙ°ePé·ê¸¾‹|ÿÿÿˆ¹üßİbI-Úó|ÓŒeLÔûXa²MÎ,:¿ıÿR¿¼£â0»ÔA¥ßJ×•ØaÄÑ¤ûôÿÿÿÿÖÓjéiCüÙn4Fˆg­Ğ¸`Ús-Då3_L
ªÉ|ÿÿÿÿİ<qPªA'¾† É%µhW³…o 	Ôf¹ŸäÿÿÿÿaÎùŞ^˜ÉÙ)"˜Ğ°´¨×Ç=³Y´.;\½·­lÿÿ¨ºQ¸í¶³¿šâ¶šÒ±t9GÿÿÿÿÕê¯wÒ&ÛƒÜscã„;d”>jm¨ZjzÏÿÿÿäÿ	“'®V±}D“ğÒ£‡hòşÿÿÿÂi]Wb÷Ëge€q6lçknvÔşàÿÿÿ}ZzÚÌJİgoß¹ùùï¾C¾·Õ°ÿÿÿÿ`è£ÖÖ~“Ñ¡ÄÂØ8RòßOñg»ÑgW¼¦İµ?K6²ÿÿÿÿHÚ+ØL
¯öJ6`zAÃï`ßUßg¨ïn1y¾iÿÿÿÿFŒ³aËƒf¼ Òo%6âhR•wÌG»¹"/&ÿÿÿÿU¾;ºÅ(½²’Z´+j³\§ÿ×Â1ÏĞµ‹Ù,®Şüÿÿÿ[°Âd›&òcìœ£ju
“m©	œ?6ë…grÿÿÿKt‚J¿•z¸â®+±{8¶›Ò’¾Õåÿÿÿÿ·ïÜ|!ßÛÔÒÓ†BâÔñø³İhnƒÚÍ¾[&¹öÿÿÆÿáw°owG·æZ}pjÿÊ;f\ÿe[Eôi®bÏkaÄÿÿÿÿlxâ
 îÒ×TƒNÂ³9a&g§÷`ĞMGiIÛwÿÿÿÿn>JjÑ®ÜZÖÙfß@ğ;Ø7S®¼©Å»ŞÏ²Géÿÿÿÿÿµ0ò½½ŠÂºÊ0“³S¦£´$6Ğº“×Í)WŞT¿gÿÿÿÿÙ#.zf³¸JaÄh]”+o*7¾´¡ÃßZïa°Áÿ- unzip 05¾ÁÖ`ls VoW°¨İan˜—¼ìGÊH"J\2 /¸[3¼yÉK^b8cˆdKüÿÿÆ__GLOBAL_HEAP_SELECTEò·¨MSVCRT³î#‹Ï}PT{âİ~2_XğÙ~E 50g>ôş (8PXF700W  Ûû¤`h` (px `®!sÆ5µ¯ßn‹l-<Ö)ƒ(nu>)Øáîoy  Oş²îY7Üæ'¡487Ï^X÷¡–Ÿ7¤£ğ7Ø€uôZFşĞYJ}ğšÀ~P²Å/ü·€)GAIsProcâsorFew…­½ureeë KER…Â…NP32Oe+E‹7º0r3timJĞÁ­(>+`T{‰7»¡SSëINGÙö DOMoNR6028´nühabf t²mh[¡iVJizhŸo¶p7'7no‚u·oÜšou spaµ f{l¶µ€-ci8Ãn7<…\6stdVxÃ35p vir!±í·µ3¥c# cl(í6…}'4__*ex\)Ùkï/atÜâ_\š[ZX÷ueX1lÛ†desc+8n­MF$ed†äW#7mm»5r¬th¿a!cl…0²k/4[knd·a.¢!rØÚ·†m p@gram Jm)ì…P6/09O9ÇfhA*mS.w£ò+8}gu(ÚWÂs_`+fÁ¸Ø¶ínng‚ot:B+œ-&dM-`9`üÃÛfVisªC++ R ì1ÒöLib´rk
s÷l{E!PÔ: æÚ°[.  <åà%,{HÚklÍ¾J¿ÂGetLa>Av.6Øºu4GeW¡/ÑÔÚd2“RaBoxRKKKjuOrE.UîÖ^Š H:ml d Í}]©,n  y 3n	Zî/d/ A&ûû…DÊember»NovO
‡ıÛho
SeptAH[¬ĞÔf´J÷ën]8ÜeAÏil#chÂBÉäu{ÏÅ6n
g_WS÷º÷ŞKGC7yC?±_í½;3#'daCÛµF^	ThsWœ¬˜|Tu	M×‚kSuC;Ô{ï½7/'#În¹¿S#QNAN -F DsÎÅFS#*18s±çœ?FMÏ¢©Î9çœ°·¾ÅÌÓÁsÚáè !M9!€œ|ğp'¨%D'0kğ²PU =Yw( ğF¨¶t¹˜h¥µÊTĞØË^¦]Îeøÿ¿°Fz%ênHˆcM£„…”A2>}+p­ê RSA1ÿÿ]GTûãü	¯®eŒ–LÅ7Ò¤wçLÿÿÿÿAÂÏòş-œ€”ˆm³„ŸŒ" ÉÍÀ«0e‚B<î<¨·LğşÿÖ"úû#÷rÍçĞoj–ãˆ“©7“B»mˆÿase CryptXph P·â­zvidcv1.L%l½dÛ·ds= []»Êÿÿÿÿ¡L°İäù¸õ¬ÑÀéØš¡úîĞ¬¼¤ÿÿÿÿ‰åŒsh«`j-aÙP¬ä‡<{Ùò ˜¨§5ûıÿÿYGñİR¼0]dy8u,Q@Dx’6b +[ÿÿ¿½»–ç}ò'WïëNÎçø_“#f7+À7i²ûÿÿğàç«‡Nd‘ù¨@­õ}Æİ åôHãÿÿÛ³µw±xÍº]í¤ä¾ËŸqtG½µ	ß.ÿÿİÿœ@…€`=µ…¸úŠ­é­ÿ˜¤û‘}¡É2öÿäØ Hâ¹ğŒP[øÕÔåØÿÿÏÿ E!5¶ıõÅA OR4…ù¿¦ú†¶·¤Ş¾u¿DğÿæŠËWÊ"vZÖ>W³{}£ÿÿÿÿê¾çÃ†–·¹ßPW»k^¯³˜ŸaÚ38Ó¦÷ûà¬i»ÿÿş>tóôƒûO`…ÂÔ_»×šmïÈš3ÿÿşÔâ#2(jÕyøeônæ. Àƒ6Øùÿíÿ//eÖ&s¨Û¨¨«‘ÚÙfı‚·¯ıÿ3uÄ›Šğ–E¹´¦}óÊò™AM÷ß¾Úõpmê#gš	wöOÿßıÒÅ*sïƒO«ff<ó~ãòĞï*mÿÿÿïÖv›aˆÈÿX‚1Gøäˆ3ÜWŸ”?1°Ì‡tOt÷9X„oHTT’F[m#Re'[PZsÿÄƒ-Onø?üNù²:ÂÕ¿üÿî&niÑØÊ5ıµmö|‹l8ãªceÿ¿Tõd¦¾Mb¿¢ÑšGÒ2_ª†Eÿ7 ÿÕNî£Õ3Ÿ•÷gÈ]?	WNÿÿ··.­A%"Ë‹AsıO×¨yàpå`ïÿÿßï7oC.ÒÄë&Ä_{¡[+Ù‰ÛDvòwÿÿÿ’œ‚í6Í¿GIÎ˜æ}ÙB+õuŸùğ†¿¡şÿÅÏT|äKÆ¤högŸN:î¦W¯Çøÿûi‹…Åª?úS:‰ò{å7!Ñ–‹ùÿîÿA\ÓürŞE(ıs™0ÃÒ¡HŞé>Åë-!ÿû¿,éüS,ñCÂ®•/-ìòešÑªHÀï>É-ú¿ıï!:.%Ü:uÚµ®¥aúFÿıÿH9.õ]%ÇHr©Ea3ÖçåVºi’šÈ²ÿÿ¦ˆz¥k¦$ø&-È—/éG!ãûÿÛ{ Emş;Ü„ê ¨eÕäèª°Oÿÿ±ÿyë}³ŠˆûtÁsø½eñmî
IÀ¶¤Tñk›7
\k£V,ÅWwÿ÷ÿ¿í,°%Ùô£~…+ò`ú7ì)¬y¬™±ÿ­ÿ’îXÉ€¨¿ÃI^;@¶¥R)—MŸíÿ÷ÿ~ßßü+:ÛL<Î«®JCj¸PÄ˜é=ÿÛşVgiÄƒ2óˆYÁ¨'¥óyI0ğ›Så}ÿîÿy"d!}LXÔi13‡¬ êâ;Ó88ÿİMÿnÇrI§â¶êÂú²<æIW£ÿÿÿûigpÇ_V˜rª±×÷Ïh$n§¨ÌWñrWŠÿû»­+åq¶hR„—Zs€¢i/oë«åKóoı»ÿs5ËZ»é’jU¼™&x8H‡—¸ÀtÿûòaŸY©]¹3I@ïWÀZÇ)oªÉÑÿ¿ûÿfT9şÌùáöä±É?"ƒcÎVª¶’.TÌ G½ÿÿÇË{nß¨æ·Û_§ƒf.62?Rÿÿÿ1GùP2™xÁù¾€oá·û¸ÃSĞ,0âáY/PıÅ3Ù	ˆW  23ìíw:15:31007;¼gîÉÿÿïş¢¹µÚ¯rÆÛÖÇ“~?Ë&ª›Ñ²¼rÿoQÅ*hwşb[ê·j/péÿ™^ÈË3p›şú¥İ÷ŸÔ`b>ÿ÷ÿÿQesÙ“>®Ï2[VGšş¿²|_÷JÃ5BÿQü³ûô¢ü?q˜š±ÄQ’Í ûÿ.) ım7(2^Øı±È½jÁ÷…ØG(TN&×¶7şˆ&ÿÿ?ö/ôº/=š´¼¶è¡ÿ‰U­´ håİåíÿÒvƒè²s²´’+CØÿ7ñNÇ‡m"W7ºV,Ù™ßSz;j£(ÿÿÿÖR8õtŸñ
î/ÊØ¿³ÙŠŒ»¹Ì:İÿ±í_Sdê¬Â?&p^Ú<ş}÷ÿÿ_D\Ÿ–l÷ÜhÈ[­æ‘ş~\y·PØÿØÿ¿éuGåŠNèh-jk-es›}ò¸ '–¸Á¯kÀrbt\ht†ı®›¿\dr¹rs\etcûü›ŸbÙ£¹©náÃwÿù›ç‡ıÿ*.„ô2.Á@7İ°®¨fq#ÿÿYp™„ç¥r‚ ôdûbö½5îÿ¿ı‰Âå
¤Û-ıuínÈD&+Ñ½uÙN4ÿ·_úØíi¶±¨S		yùy´ˆ‚ÿIÖŠĞˆöÿÿ÷óh¢E;S^Cbésã8:Ú’:éX&êSÿşÿ?A( Oß±­J,øLÈ¿×Ò¸º“æ_ìÿÿ4$"şq‚×ë9ØExºUU¦îé31ø2ßböÿZÂñt]ã=uı;Èxÿİÿÿœ9ê£œK8$oİ9]²µ·Å+	€²â¸oñÛ¥¤.¹Ôm¬#¹g)ª¯áşÿÿÙœPö\Ğ8hãª•yÃ}î‹ù¢ä¡ı3û%.Zn~¨³ë‡Óo Å¿şÿûÜZ|$ÉR[[^Ç7]¨î®ˆş†Œÿ°ÚúGøÿ#ò;ì™nôŸzi–ŠˆIº‹K_KØ0D© šxyEÿo§—o²tæ\“™b0šjÿÿÿÿ–£ôŒ\¡À g0!H¶´ká€j,Â\°ëĞ]7øÿÇC9J–`ã¿ºT+WÖøœ‡ÿİrûÿbMT„™{ğ†\'ãæşw6ÆßşÿYjÌkó‰DÒ*]¡ÈNáNå£Ú{û…ÿ‰§ÅÒƒ“^¬÷äÎåË³ÿöÿÿH=ÿ,8@øî\ˆ,ŒÇQÅ)nwÊŸ¿Q½İÿ)ö2n>sû’5ó†j.~§§ÍüwßúÿÿÆª¼’Hß_•€f6­Sº®Ñ—_·>N(5aıÿ?öXz23¨]CjÆ0Y•¹‹Éuáåî°ÅQĞø¥Àgã!IÑ•ó1-™KşßJ„CÉX øtETü–3b³jR}tû)u*Ü+U½lD"ßJeª¡;pµ@ûşÿÿ’Y¥Ê@Ü~¼ø~<ùØGıdyai=s/¿ôØ®%±/%ëîØ‚º»úlÎÿÿıÌîì	ô©µÇP°ÜA9?Röv(tH·øn‘=§«ËÆ““©‹±ÿ…Şâ³Ï%Ñ}'ıœFÇ/"šnßBÿcË­IŠ^ğZİv{šŸìKÿBóP¯º­Ñ­cx4#CÃÿÿ/ûİè)z+k+>ÊÒræÿÿÿóNMÔì°•,.Ü_¥ÖLÍG©½qI<Á¬q¼ÿÿ4y+whaÄ u¸bSƒ´şÿF³‘È‚x<$\Çy²¨¬Ô?V/ª¾ÈXÊ|[6÷bÚ]à¡¾Æ'ÎH•qxÀ·¿ÿÿ·ˆƒëÃäI„y@½³ä.ŸK…NĞ%şÿ„qãçA A¨’Ã$«AÔ]Ô?i°•|ÿ;k[ïZ·òìõn[‚gßÿÿÆ
õda/JÎ?³Ÿ—1=QªËDÈ[Ï/0ƒÿÒ§i55zOdÁ ‡ŞÿÿÁ(ŒH®+ÁTİT¿é0ü”Sí©/}/*+İbÒÿvÇ†±‡»¿Éï?ôsŞ·}¾âÈä×Rÿc½ñ“C®~Á_«“ê³Èèvÿ·b/7Z`ªÕDÖí¥ÛöşôK¦İ÷œuïÄË†”¯ôëÒ6@e‚ÀÁmá¡¤ñÙM”hşØ¿àø³ß5—
C´¸CÓà›üc¿Õ»M:árÒÛÎ¹¨k–ÿ?òûV?Äf÷3ş>›á +&}4>Æ_øï:N6Ã²Fİ+T^ûÿßïãh%>;^§˜p½–j|ÙÓçºäÿ¿ÑÆ‚›¦ñøíĞ÷™Qê¼¹Åâÿÿó=ù°ğ_Ğ:#{×~ ê³BŞmÆªç°FÚRÿ¯ÃÿØãğÕah4‹cõ£«öİ]êáò£Æ›NÂc'[©æ¢ÿÿößE4ÂÚ)#Fğï(0@ÑşôÖ*"ÜK¡Èÿßø‹Æù#y).\B97R†’z¿Òiÿ¡	SGlÛ%‹…Õb8ôÇÑqşìV„xÔá×èÄ7¡¡¦ôÈÕÛÁûÿ(İİÙ[¦Ìú¹!u0	#¿[¼°ÏÏZ½ëÙÙºÎ¹èİêt
yı¶Ø¹˜D8Xªˆ]ğ¼wKDQìÿ–³¹€ë°æµ€–h=YÿÕ_ óK/SÒxñ†#şQÖ.#7pú¿O¶^1dÀËƒI
µÒÿá«ÿ´"‘ÿóÈJRí3WüÆÎ‹'šîB?½œ.”PQc‹şîÿû °œå‰7Üù"êxåRŞÓŞÔä
tø~ë£­n ƒéÓÏëæ§­ÑÿsºXÃ½½[9õUØúÿÛÿn¯Ap;7~"Wj|ÚV!Ì´¢ªsçL4ğ¿œ¦Á’D88ªÿÿûş¬˜¾Î]»İo0õÓù,Ìÿ-)-?ÍØÿ±•ß#¢y‘‡ô så~ü?òÿÂwğR0òjğ5Ğ3ù¶qëlØ¼4«IuØÂh-–ş­¿ÆĞ™‰X¬úàâzàıîé×ÉÀwåPÜêód ÿÿ_Ü1Hò[›6ïò’V¦Ê b6".Fÿÿÿÿ0Zƒ›{÷ÒfKÉTªò“V¾n{’~é¼ºu;M‚o—*Šıä”IÒ- c×,ü?FÿQ¼óïĞW·Ü7óJÕOÖI¾‘f_úƒ‹]á©sÉ{™e1²¡À¥~²\Ë!ôL‘_i.ôÿšoÑ0­Å½è¥¼ã•İ§ñ‚tneşÿK÷Sèä„a·Î*+gz.3ÿm[2FN	‹·ğÆVµd2IGàÿ‡ ¿E*d%v/L]•:CD×±_ˆm€RCS¯Æğ§–şÿÑØÎÂ7—HÊIÕl{ñ€jó_àßÿ^ŸgO©ë¿k;#>‘ÄBcv^ºğ;şGÜ%Aü:¢Ãº·×Ä–şÂ`^¯‹„ŒB”9LS÷ßş÷r5E¨¦Ğß:zú§Ã—ú¿ÿ$Ñ©Fƒp'Hnˆ†Á5g×ÿ«ê;A¡J´@{“¿ş’9â¤á½ÉY­TáÜ)5< +;o6ZèÿÉE½à€)OÇ_Çˆ“Å®¾ñ±¥UWk™0ÜÕ9çİìo‚FeIHA¢õ)7ø·zO"šøbúşúlÿ_ßà	5!ê1Ô#¤Ê41zbjMş³şG;xÙ;—cş“0àˆy<àÿ%ô@‡§¬šµ/3êĞûÀ¿c`Æ1¼ş‰¢`7Œ¾ÌQëpÒşöÊÿÿŸs“t'YÇ%¶ŞFÂ/’ÿi;éîÅCö¡Ç~©zƒSòó—<G?åRÿáÿ»ÛSü€rk|è»ÍF³6ÎS‡ÆÌ0‰C~ŠÂƒÄ±Ifñ"µÁ?ƒhûZºKÕQ÷lõ»ÿwpXÕsªäã©ø€İ¥áe”%¸ôığcòÅı+ÙqF‹¿%şeŠ€[‘×‹5]Å<¨Æÿv«„£é½_‚¹8ÔKj»ïıK¼?|çwëş7C|é9Æšø—Àg	i¹¾QİdSÿşÿısfÚÛ.ih¾¾PÒ¦F)%ßè}*jßşÿö÷©Ä_yĞ¢pë"pÍM
F§Z^`úÿÿ80"^‰ƒ>Ô“b,Æ\§¢úüc9[úÏ»Qlõ~¨Â2ÄşÏ #Şõ%#Ø\'Á¢3ø/_ˆênÚX:çK—ƒÿØÿÄ˜ê5İºHUdm.8À˜õÿÿŸUƒ‚ëã@Ï.gQ—vï÷ĞæÏk*nŒÿ-üä¦ƒì:|D›¾3[°À»Õä¡C¿À\	Check Wÿû?XjÜ0[Šµ£Çb‹È®àŠÂ»³ØJÿFßÿÿz¯ÃÚÊ ßSK7NŸ>øìÿõ“f
K_ÃæîÊ3Â«.“q'B¾¬~ì[ßÌVòÉ':g+À¥"ã9ÿˆ—ß ±/ § ‘÷ÑVÒOwxÿËPê’{}Œ<ÎÏxõÛıÿ!:Ôƒ-ó	{À!Jµ%“Ú³R¾ÿÿŞÂ|êˆ·™HÃ.cQ’^7¾	ş/÷	tîi¯Ô#gŒ° JáÿÖÿÇ¯r–‹F+vK†Î¦ËäÒ$9ÿÿ·ñá¼ÆR4í,³ù»Æ,'•UúYú_ˆ{tprefix: ë³~/$ş¹&\¯ñ¿ıKï2‰YÕIàúÿ£‘Å(#x“w\êàÿÿÿàgÊFß)~äº‘gÄn­†¸­éèó[¼äcé3óà'A•¢œå¦ã¤Ü(”*ô£l&COBÿä¯T9œ®Ã#ŸC]Uü¿3KF(`<"hÛ†şVå€/ZÏTŸî4ûÿÿï[€a#Ã®QAP˜uï´Ê ÷p.h§¨ÂKóßğ¿Œ	\Q»§—`Ü÷æ#'_¢êÿÿü®¢ €„¸‹gÚ|áŠ„›CpSÌ;¯¤x·b'›„ıåo{èí7öŸ?Ï[E×Tÿß2†k'ºX¯¸îÖ¡[z­[³æŞ]ØĞKD$4Xht9:/ñ/L!7'ÅÔN×æı?[(C„¿¥¾»Ş-ÿaíˆDK‡ót7.ô‰"É-2g„‡yş‡	øÿ¿ı§Û)¾y¹7Ùîé"=|‡lÉŞYÂÿ%şè{ mê–tŒ¦òMÒä³2NÑ/ıÿÿ	®êõê^!úæ‰ÍN6B½‚ÅßDõØ~â?øßø»c„ñ*8PóÌş‚Uoïû3/êj,™Yvã$ÉûÇşZgÎR­¸#ÊÃ†Ñïo‹÷ÿ
ƒÏ;újCŞ7|²ê··W|×_|øß-Ğ—'³ŒĞ”åÿÿıoµì1(síe(x#QXM±B¤È@›‚øKıÿØhKı\@mö`0d46àg,Bÿßf£>"F×0K’ïÿî|ü±Şâ[qÈÄí†‘¬w,ìıwy°u€BNˆaMñì¿t¡`4EÄz²²¬Ğjìªü[cÿ¿TG£-n()kQmx_†›ê^øÿÿ#¾>³Ö‡-sê“GÏGN¦÷Ã{t^“r«Ø¥ç¾#£¤û—üŒÿ%¾Ğº¥9ÿ~ËÚc"Öı/õÿ;ª‡÷7²ÏR#hâ¯A¿6+Ø^±¡Û;”øwóm¬%Ñ,yè¸mÉ5âÿı‘l8¨…5XS¤•Küÿ‰|£ùeµ¶@ñh@y‹h1ÿÿÿ/_47ùrY¬d]–ÅÈÑN<#Lç<B„üí€Iå±àbë€Áô›¨èØßŞ1´ĞkúëøÏŞVÿÿÿ@JWøãáĞş0wÃ*7ªëûc*Q˜U¿áoüËïrsğÏO€GKï¤“¥}èÿõK¤¢Ùòf·~i7³~writEİd'‡úoğÿÿƒGG•~ú£×üÏo%:Ê‰óc˜ÅSÁÿß“BËÎb&›Dáê²Îªğ¿ÑÿgP¹¥\şäRÀZø"yõ‘Îüsg÷áZØã±Ç/˜Hö-rJWIN  ¶ÂŞ"Jef  ÿ7ßQŞÿ ö•Ò†P·ß2ã¿Ôb5Š
^´qçzî@ÿÿ¿Ğ¥í øe3[É2J¶óâ+nBÆ{äÁ
’nkÁ; J DÉ¶şşTotalI64uMB, Fşe"™{È Qu$MÅ÷7DiskSEºàÿßxASMemory(Avail‹/ßmÇ[/PR)=luKB mï¶°/`Usn%m[¡Pà TmPí_ª?ƒNo Syst°‡b«‹Zeü,h*ŞÚXtus Uh%£í J5í]ì%ØOffAC PØ…
Øer-ãP,ròäTMSSDFXS{ìî¿R	PSE36PATMCA²GPGE
  ³Û/TRGFp£lïICGEAMöÙØ›noD VMg€
MW…p±ĞÁó fôÑßğ·÷CMOV(PXCHG8R RDTSâÂ~l3DEw ex2ns»‹½­øs'MMXBìw!CLFLUSHc.XìE2¿—/F8ÀºÈPU•P%»5×î¼GHZ/·#ROğBöÏ[COR OBILE;!Ûá*³hs@;Úätypnppl©Ñ6J%uc l	İZrußfn£EtygAutÙ«xöÛncAMD «NussK¸ ofF­.C¶}5Úou,T()XŞöíLakuageC İf“9«¯sW;mp£C®mNk3Ğg¿ºõn2X#OS 5¦?µ-˜çv.|(Bu'Ömµd†)'ô (¹&¨}YaÃkMòJÛööºWñ XP+VMS‚vé·Õ *iüMa‹ kU õl±½Qß
›>bifyyCtxÂæVÛE&sÓÖ€B6EÀ>~¥Æ98» ¾¹Š98œ°R2  zŒ6ÀCCERV³öo!TLANM¹††ÙWIìd¿Tô8ÃÆ¿+YSTEM\CFrºÛÁë.‘olå\
\,ß°ŞOpòrT 4.0 jm!`}E-
	@?¿EÂÖF²dšnüA§a…poäZön2Ce4/´ìğrdAo€Xb gK9ºd“c¶fn$láÛal8H—eW‹mlˆkÆaÌ˜{€ğ+HSVi'¨`ä03¾ÖpCZ(3+/+ ùµ2K ¶àÜ@† 4±Œíµ7cœ!rÿ‚Ú—buffµ†Í™Ş DiÌpJ· d#!¶lË4¾8m/*6ªìlew7eæF*³2éaCW0¼…Çä–ìRkĞ    *5†‚yMÇ»4lÖ	t OP£`wƒteÛÚooHyorZ-ÙFésyG}s?ì9‹FŸ/8Íös {Çu«jC'$½dyæ
Û7ic~Lá$v¡ğÃo?ubsiMed'e…=øy––-ì¿ withƒoÙÈŞÀ(g	;>!ÀB6?#Áì ¢KÆ¯°a ícf ‚V”Jã‚;we3¢ªbI ÅĞ“¾ •r@BjÛİe¢Ëó<../\  rA÷Kºê˜˜G˜t´ËÀNEL_Äs 5ìæ¢ì9àÿ ğñ¢€„ *UUU•Œª0JF‘=ìà©À+Ù{©ğªØ²`G”ÆC  Ø¨ƒ uGèş 	-]]—Ì¼ "ü
³S“gû`‚y‚!/¦ß¡¥}7òóŸàü@~€¨/Á£Ú£ 9;„oş@µ»Á†À/A¶/Ï¢c¯°ÿä¢ å¢è¢[_~¡	Q¯ì·»Ú^Ú__ÚjÚ2/ÓØìÑŞàÀ1~9Î;VÄÀ7£¨€> ëÓ4İ—XÓ4M	 ƒ2	
ì6;WØ`[ ƒ2!5Aƒ6ÈCP3RS6Ø`W_Y{l¦éƒmpl°r«/€³d°Á‚ƒ„G¬é‘)¡l°Á¤o§·ŸÑAÎ×ğÁzFg®+® „ Ö€n—,… =Aß¬éº+;ZÑt¯¦x—×Ôóe›®ãçœ0Nm3MÓÒ:‡w–´Óš®{4òæ'/Ml€ÄÁ3 
¦yö¼Ø	¬
'O<ˆ\,yòä‘Ü¤|“G<DäxyòäÉÔyÄz´ü°ÿÇn4G{*  €L2e( €L$H „dÀ.,!€X?‚é°È; J$ê   `›Ø/ÀÈ Í–ì$F @d@‘U@d’“"FïxÏƒÀüÿÿ5‡@ÛA¢é÷{§ÒÇÛ~p øiš¦éôğìèä¦iš¦àØĞÈ¼š¦iš°¨œ˜”iš¦iŒˆ„€|¦iš¦xtpldékšXPH/@8¦iš¦0$aÓt_üôàØ (Û·~‹«ô˜pì /¸€p»(ã ,PSTƒ[”PD?wJVP“¯ù6  @ ÈIäeú@œòCäPÃ$ô€–˜çŸ]Ğ¾¿É4ÿÿÿ¡íÌÎÂÓN@µp+¨­Åi@Ğ]ı%åÿÿÿÿOëƒ@q–×•C)¯@ù¿ Dí‚¹@¿<ÿÿßşÕ¦ÏÿIx<@oÆàŒé€ÉGº“¨A¼…kU'9ÿÿÿÿ÷pà|B¼İŞùûë~ªQC¡ævãÌò)/„&D(ÿÿÿÿªø®ãÅÄúDë§Ôó÷ëáJz•ÏEeÌÇ‘¦® ã£ÿÿÿÿFeu†uvÉHMXBä§“9;5¸²íSM§å]=Å]ÿÿÿÿ;‹’Zÿ]¦ğ¡ ÀT¥Œ7aÑı‹Z‹Ø%]‰ùÛgª•ø¬èÿÿó'¿¢È]İ€nLÉ›— ŠR`ÄwÿûŸ}ÍÌÍÌ û?q=
×£pø?Zd;ßÿÿÿÿO—nƒõ?ÃÓ,eâX·Ññ?Ğ#„GG¬Å§î?ÿÿÿÿ@¦¶il¯½7†ë?3=¼BzåÕ”¿Öç?ÂııÎa„wÿÿÿÿÌ«ä?/L[áMÄ¾”•æÉ?’ÄS;uDÍ¾š¯?Şgº”ÿÿÿÿ9E­±Ï”?$#Æâ¼º;1a‹z?aUYÁ~±S|»_?ÿÿíÿ×î/¾’…ûD#?¥é9¥'ê¨*?}¬¡ä¼ÿÿÿÿd|FĞİU>c{Ì#Twƒÿ‘=‘ú:zc%C1À¬<!ÿÿÿÿ‰Ñ8‚G—¸ ı×;ÜˆX±èã†¦;Æ„EB¶™u7ÿÿÿÿÛ.:3qÒ#Û2îIZ9¦‡¾ÀWÚ¥‚¦¢µ2âh²§ÿÿÿÿRŸDY·,%Iä-64OS®Îk%Y¤ÀŞÂ}ûèÆßşÿÿçˆZW‘<¿Pƒ"NKebıƒ¯”}-ŞŸÎÒK@üÈİ¦Ø
 •Œª*ªJFUQU%£É¨ª’ªdTUUU2ªŒª*JFU•‹¢!ª9öØ­;€@€¦ Qm$S—aVAO ¡ í‘½Ğo¯t sI+h+èPq©Ôu›{è /c%l_s]·Ír5ge!û€h3sP*b»²1HMAë6÷V~Yï ©¥R+t1È ¯¬y+P|î	Û\7C³n…1l(ëŞ¶PW š¯é+Sf¦¬ëUau×™sPĞd©+‚îs¹&ê·ô?Q’DCHìÁ{7ƒtínt¹×uCsG›mGŸ5t]adUUd³e… İ€M(Û‡Ív³WÄ ÿÀ†, # ö6,Ìf ™3l¶²2,[Ø/f2,%Ù°ÿ82/ì’“,  ÿk  `c³e2&Ì2í%K/3Ìh·l`·™f™2,FöÂ6™_™ÿ2{²ÙÀ,’/ÿ™32…le!,/`ì„e™Ì,Ìf/l`/[2Ìÿ,/‘0ae …ƒ%°/é îØkË/2œ²ófÌ/ÌfñL2€™3fa+°–xô,£ªÊ ’QU%UÉ¨ªªªdTUU2•Œª*‚pÜ€[E losPÔ"[D7E´etÔAddFD1ÆÏ´"úuleHdA¢í/UÁlushFiB¢İ?#DeˆèXëS
	ttÛnÛ€zuslsc˜ Üat	û(QkA4C1‚½›AExit …¢ š‹"2kÛIsBadRePI”‘kgDyD'½©A çEÁÛëŠÅ (X(E1(¬okcŸ²InfoLŒ:töaoeÃmpihVšÍ"DFbñÈ–"E™p–,X¯àıT¿NAosDimK‚‹moL	KF ´6)iaÂ6af¸d”Ú.[de³}}ng’WgI
€E1sØŒˆf'9¼lR T‹¬­0v—zUnhyd Î°»SDâ§ter×èÍÙüAÊÑmf wÔckìíî@ÊıuplateÎmì‚0k(WaF^Œ7·GS¡Obj¿”0ì9,ï“aÆ',áIdÂ?Wri¤aöBlÁ dOf¿¬¶czzlF£ 0À‚v#	§ÅŠ1inç³G0Ê˜KwR²CnsÆCw˜"r;İlS»y6(¬€e){SE\ÜÂ³îId"pyÍ`2¦å™f,HeÛ`4wpòLkv£Æ‘1cŞªGA˜-
áÂl;µT®4EuX¸›åyAll¾K6V¯	S-7OEMCPOjÁÆ	A¤LCÛ%ììMapÁW½He)¢ÃYYIUè„½Û†¯DÅreöö’µ
5†Rtla%ãçwind"Ô¬Kî/†ommæ•ne%) %4ğ/XãX'Zoö­-„½ó_able¾ÆlC­åoyqÁfI`Ú+F¬`Vçai©Ì C¨r§{›`MªBy>
0¬% êÁ† 
Á¾PzDIz™tKey¡Ápk¶‘asË1x#‹åÚ›gOp­ ÿ#›ÅŞÄc©­ĞQÈÏ\,Ò°'a†aÙìM?ÁZmƒeP›/e 
”Ş,ìDepr3™(zÁ
à
‚˜^Â°rg“ureg`²`7g ÁaÿAcquirüA{œ#2H+Ç EQôsÀsDD£8‚’usİÕT1lo/íŒŠ%DC!IÌÖctÕ
@6B_^{vì®>BltQBkO f`W³ğDJog±î"´,;Kilñ 1 rtdam›E.>hÛgú‰+lgIÈ%d;iÖ
üB‹¶f±™Zw7ısAâÆ‡!agì€Î½`Fê!tØÀÎ"—AeiƒÙ!šcp§›ŠÊ°tfPx0‚”¤eDáì2œ;#6BA(¦ˆpFûÆ–"½@FØ‘t2O0·lA‚EáYCa;{,».ckè)@²	én¢–Á°ñ[ÃM÷oëÀ†ÅnsTeUŒX)¨Sö˜¸´Ö^°!]8–½`o†est/ÜÜg?¾chÛt@Àˆrêÿoÿ/®	Û%		
Kğ…ÿwãğûÅ–+¹f d&[/ğºa»oÿ+&>&8;	;ïÿÿßÊ++<
ëj=4ğ}  -# 9·¿ıö'	dğk%*l3.Önÿ·i
	4**M†„¿½ıî

2		ÿo­ü	v¥ ]/.0’,ÿ¶µÿAkN]@#­û­u%Eöıíÿ3flf[C%dcÛív #)&nì¹í,,‘!&ıl{	7$´'ívs¿%"#Ö){%km[n ¸ÿ/ke	Î$1)+Ûş­mõ
ß)
­¹›	¸Ã,{ëff’	o·Á4U;·Ö^p®f
æ¡a	NM½â{W	|<GÛ¶í7B dí€û†o89H )- …®»pN!&!“ÎµímTb‘Ûo…sDC4¥#u»0Øöä
"í®½}¡#x5c&R¢ÖÚ¶lsÀªF§áÖ¶ &&pBÛxaö
l[×¹­µÚ 	 ›[ÙL.Á«$¾.Xs1]#!3¶(D/A'¯…w»c:+4&NÙ°»?"(7+´FÛŒø°Mj°í¶5ZÀc­$vJµ¸u0#¿1"	BİğŞİµ*3‚%çZ'(m¡ÑıZ=İ4o°­½K‡_ïrØ¶n5Lqar$I®Ğmn@*Eº·ß‘
 v@	(CC{3%ÛaÖ&FÂÖ.|(T+éHEû:èÇ”°0œ¦ÚÖh7H38Q6š)æEnk$Ù!Å
oeuİw¬$B1:Bü_ú­ËÒ>/1,.@d05tÅêI3Å^‘İ»kC“u_×	xáä%Û(˜º‰O¸à¸ˆ·¹c[º?ë¶
.÷ßmÉ>È-03xi÷·b‘¶{v/8™!z
w»¥qª…a)Áo?9n©ış]}ŸkuOáÿwíK³QğXğğZ‘·F¡áoÙåŸ1Ûï»T&-³6-w+S½5¶ÆAÖğ>°
< IÜ{óıGEƒXzYÍùÂŞ¹²n¬3
í	lz/ÛşRlA¬’-=+	-€dCÒÿ¥Œ­ğ]^,=/*´[o¹­G<œ7×ÚÆÖÚ#$¨«ú™~ck7†V*.·Œµ•W¨),°½ooS	)­&'@º½´-8Å;,/^N1#êğj!Z¾Páƒğ[ì&:)¶Àş3A+rA¶^=öÀß>+9¢0/ Aë¾&ş #5*váÆ’@
6©'!ñ7ZÈbT(.¼ğ¶ƒ	¬D`~.¿ÀKß=#0É/699+ÎÁşí)9J9*9K89 )9(üÛï9GX:+I+76&%0÷öÿï´%"½*8½ğ÷ğéÛğ"İ¥¶möü ğgK=şíBíÿ†ğâ	`ŒğN¾ğÀ ğ«mz6á|ÿBc	sãğC"ÊW”,{ûÿg AğçH­¤{Bğw‰=Fíÿ­	tl7#s€v^£ğ·pÖ¾@‹òÛğh“)¦ß
ÿX&t•.Wå‡ \1°Fƒ­é!)€5Ô/ñ¥…ÆYB”–¡c[¶ş¾N^SBÎZW:l>&Ø	…`K&ØÅ)	ÔşÛÿ•Šp/ğvAi»1iH=\øÿ¿+˜ğ^Š(ğó 7'N,ğYôúÛÿ-/¦MCI^hVğ†7q£Û

sC\×Bí38Æ	o7h¢–R8Å8n