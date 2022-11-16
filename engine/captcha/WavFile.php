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
    pro�Z`���KXKϊ�@�O}�Z~K���8��7 Z�Uȃ�u�
2�
P�1;Ѣ
R/8#X�{L/�Cx�N���2j �RlW�U��G���H�R�h�heV�eu�G�X��)��v�(9j0 .k��}D�
T���\�Χv@�A�`w(�����9�� ��&X� xq��$x�E�(�H0X$��9��ɂj�9�|4��L)�x����"!�m�=@2E	�u?k
�~�-��f�f����H)��v$>uf��P#{{�f�*.;���V�T3/���V����t/~�89���fM3�3�&�P�����c'�8��@�Pyi��C:Q�a�
��+}��/�G�GG�B�NC\�s,�u&u��$��+����d0��_���
��������X�� ��fK&ū��Q$��t&3��;���؍<�v|^�c�,@Ȁ�����  ����Hgt��  .A�C0�`h�X��{lw3����j�& �$Sg�/`@�*�e�f+;�gQ��. 8c
J..�q�V�
;\.�@N�h�{M�����D@��5��Ƞj 1�%_�
�EP�D,�D�O�`�B�����V�;r\�{!R��	�B8LBq�����D�-V
	�!h�#�Nƅ��A�āD@
[D2.�8T ���F` �w�
	�

�*ދ�	�/tM�PU)��H�QW
�F�F���vw� ��A[E�y��L���jp"!�E��\`��f�*[	���fE?jX4[ �A����K� �Vb x�dψ#��9R{H��7*�_:eP�`�㳀�fU �`�aGv�K�<��LD� �Č��=��b�g �� M$�5j!_`��j�D�B��bX�] L����W �uG:���t� �!7|�
�7dk�|F',��R����; O*(O���|
xD1a	q-0�>a�
Q<m�v�X5t3�
&��c-4Ƅ/6|z�B���N4p~��
,�m"��p�_D��5�U��
Y]R��QPD	�s�6����R��Re>}�e?�D�0�QtoFЮ��0�Y��;�Q;�T���j"9�k��2�!��`�;:tp�<B� �	�|��Q'DDڰ��9'o�S��7o_�:�A
���� �Dv��:�)(��=�~��v�\�x�D�����������\-!?4�e%x��Qj �3��HR��ɂ$%D,��<�P���|��A1P=Hpa}=!Z`��"n 0n@¬l4 �w��b��9C�X z5+��D�T�C@X��  ��!�ըը�B^!�ը�d �.��
iEi� ,i 0�mj#��))4t�X(9��� �A�,��6���djQ Uw�<��j��� A0�Q~0!�`��@N�)
V�ű���	��X��G�d�������	m�hȼU��|������ʼY�]d�#���u�{�dR���}\QjL�C�����DM��[4�1���4nL�ۓI���F��q[	�4���!+�   q �a@C"���W�K0<%4mEV]k� ���%������/�+��+m;��:���pދ�+5��'���ػ=��)I���"aI���lF싢I��
F�� �
kRb�E�Cx�$l%xPF؈]������"����
�+�Ƃ$+�}+�_��1Q��R�{��kU7&#�C�@� 	��Lamd�2�,p��̆��Ry�{-��｀�0�( �`��/0�X����0��6ԑ �_��,��u����v�<,��4�h� z���$t���j OR:f
�8�88ޘ�A�k	6������R�V�hd���bnQ�%_�6P<��9j�I!Tcر9�uV[9�u�0Y�;�b9u! ɣ_9�">��WA�I=2�B���ӹ	�/v�+��Bq������R����Q���1�*P����G��AWBR����!�k��$�Ax���p
��U�;ƛ�Vc�p�t�t��rD�( x� ��P���O����rr����r�ݙ(hb�<`r�`��"����A�uB@�a�4Q`�"�l��[rl��l����dah5hѐ�a@��B �`��k� �iҋJ�L@��D_�Dƅ�t�C2a\Ӵ�/�%
XpQs}�\�QФ�C��[R��T'��N�{T�i�{�T�Ba�D
`��R�%C <��kP�2P��{L��0�LL���`�%�pOH�ȕ0�DbD�H��%CR��P!���d"@@��q#<c�H�<k8a�A4j4k'�+8t
D�����3^_�v�u����������a�\$��oD��%_�C`�u��`�p$+ԓP���¦+��
���K"�����*���4-�Dj ���ty r8���ҕ������tp�28�Ҕ�5��8 a�8?��o"\ #����O�� E}�A&@43Ā������3�؀��5�3Qp4=\b �?�?�'p���!?t=��*�?���`g�=?�#�d0�������
,�gP=X���?0�: =��D?�F=^�+�=>��:>� �=>��)�S>�>��9T>�>�&#����P��=� u{É�9�\$��tɐ�A����fd A��f䒳T���e 	d�fX�D'�h��k�u�e� ^�:p�X�0���>P��c�d �8P>@0�胍pԑѓ,)l�h����I�P(<��B4�{,����@�t��^lHx�Fj�ۆx�St0`zFv�/x�t4b K>!��od�6Ԛc �3�d:pFe��z��ȍ��>�����G Z�J
-�@�dQp̣HJQ�7�a<��:ka CGd
	�x9��S\�d�9��6�c���9��5�����RtUy=����d|�U.$)��`	�N��
��u]j@����A��7Y����Q}�x,� ztǅ�P@��P������=��$W	PV=x�@�!x��t$,�*\F?d䲾#L���x/�`u#,8���h���L�P�Ƹ��ٴ��`�}/$	u<$-����<����`�u�Ԧ��c3�d쒟���0��Q�u-,`G����$�l,t� �a5 5�K�͈]���Eb<�,��,����b����b	j	���9u#q ���H�"����*9<̐d*��,
�h�6��`�d�!#d��u����@\G��\G�l T|X5X	�|�A�(��?q  ��@��#G����b= 1YES��)r��kX6��o=X0�jFj\(bK� FD�ֽ@a��%��(��R6D�	ita@Ҽ'1� �q�� *�HP'������LG	���T�@����nJ�P=F� "Un�\�^|���\�# -�Y�,ؽ���QI���$�ne$��nx�^r�U�k�@C��n�{V�� �F�S�YE/E�f~0�VP��4 ��<�f 
��W��c�)��Qj�U��'�dSL���ِ|t
�3H*z����PF��
�{����uL�g`c��u^��]^�`�H=����;�';D�����_k>���l�z-i��E���atM��U��I���3�wID$����A�Q<��t�LjId�F��5 щ��U�P� _�Aq��� �ۈ�"=G|�lVQ�
�"/.ܝj�kxhNX�vC/��Ę�e�d$�k �v0Dp$6�2�� �#�+C��TQ�u�S�L}=MZ ��"��Xe�?�'_�`�Q<V��8PE
�ܥ�u��+�N��5!�	��k��]���H���E�%`u������XX�OuY�������S �uY�uIxHum5��QI �D�ܴ�N@;��0�"��ح��E�_zP��MJ�6������}�;sYl@�q<��J+HF�E��NF<�<�Q�`U0�jɠ8 Oj�@1�
��Y�!&�@iA��ưg{� 8*x%��v��� :�J,�{��t��;�sF*�xB�-9p<`�z��v+BB%Tף�Or�,F=6�yNhB8`"���J6"���'!P��x"z�`�}D2�:@!X �.���~U�X1�����������
E�;�m�t0�Z�~�DڻuK� �
��i�]���V��3"a��$lIsZ���6�a���V�0&�U�V��V��ض=��`�␗� �7��LL��+�R���+��o��w�;U�t&b�-�u��7��m/�̳�8e����C�'�|g�X�fE�
՛�c���9&t>���C�uA�FXГ���_^��jFO��0�`�'@��s6 �������K���f M��&"�M�Xci��mHv��p��*:[m;k�eT�,.cM�MK��@uL8X���{� u����,�e��g�D�[M�[C.hƊ� �D�R�!�[��ǂ�[��L��-� �s�k�e{�U\U���\�aql%[E�Jg+���[�U��c[��T��-[0�
4��M�C��Z,,2U�<|�M��S3���r�S�0�a�U�1F8���6�Ja���w��t�8���E�-��ܤ���x����13����#p���/�a������`�e���b-�U��,�9��x����3���n���X��U�b4��ܵ)
 ?��0��b �d�ˊ�u����ųFT'��x��rJi0+�% B�ou{UP
�<ar0 ��<zw, �B��4@rX�߈����C�Q����ԁ��K
;N�Ů��"w<�ŘC@�gkx�INl�Q-p�@�i��N<Gq���/����&M!0�1 �� x�c_�I{)%(���Eي�	y�>PN�b�l }>!�
�`��\`�>VB��Au54����B�
``��>��C=�ZM���@M� ���p7�D�:#F�|�t�	�k�ihNH$�ő,H��s7�b �w�:L��3��"5<Y7������hS��]���������<�Q���D(��$v �eMn�lWRe,�U�٨De"P��5�dw.v��$t]��L������c6��+�L���<�GL�jQ_"���.�j�j{)�֚E��  ���� ���r$E�� Pdl�-�~�o�໖M���T2�v�ZTQ��H��{ ��aL���2Q��d�Q���?���4ƪn4xT�0,4F;>��N�~�� ����()�OL���̪��������[hGA|����2�7�j1\	X,�!�{�Ǎ��}����Vbr
[�R���j��@�����Q��@��P|ه����� <|�i(�|5R��1�PV�E-���ލ"}�=E�#L 6�ܼ�@H�"� �ZD[ P�XD��r� m@(������3�5V}[������!"`g��X"C)��_�W�������E��ُ�b1�;�sr˄#5�+�x ui:�ıS�+��L��x�=~8$��u8�����c��.�م/���(Zto����$�v�Wm0S�&@����,�`��zK싕x�xT�~A6��8"c�)5䋅�8cA�x5�c���y_8ܕ�fX�n ���q� �4v{0i�t���6 �S�� vl8���"�:���l/F��"Il�8 v���a@�q�� �;;q08�q`��ӍIi�xv2|�t�� �$[������,u �!{�7JT��軐��L�BD(q� d<4d@B,B� $ d@	B�� d@(J�-�
�Ŵ��q�ϱ��E����t�l�x��Fɳ`��)�y�j$����B`u�x�L�!���E�t����{r��%��H:�����x�t-���V9�T��*|��`t8J`(X����ǕD������k l� �`�*|'��5%��P6@DL )PB�-��+���e�}���}�������0��@q��0w�c\6��ۀ
y8	����
�<	�
�I� fPӺ�����5�G�(&)	�,*�l�蝨w~���*�$`�G�Htl�R
Q���� �.Fu�`9��+/�Jܨ�xx8 
��(�P4�5l��
W��+��q(�I���V��	�L|��$�U����o�}��w���O�
	����� i� ��܉H5��0n�+�� ~Et��M�0���zB4M?�OPQ�P�:*r��>uA����@p������ȋy@�*���p�݀�O
��b�����aW�[8Q�P����(���ׄ�;E���0��� �[5"��P���)(DG^�g���3��E�^��HE�����Gq��3���B�5>�#�U�"�T��W�W�������Y��+�Cڨ�>� ����1"�.P8�;B�&�,�>��@��P@B _�/%@��(�� S��e�O΍�>Y Kr�,AwP4�	@^P�B8
L���W��$��)�4��$. _-؏b;��E0
9���x�(��$�9����v?"����F��L�;�tg�u. <MAҐ�P��6 �P>c@�L�%xp�k�?���k�� uo4UV��~�5{;!����U�Y��`~�G�
ۘ'�G~�	�!� ���A!ĈE�kR�%�~y���vت	���
Y�lg���N���9ah*m$�0;-�WB�Ȩ!YqS�m���������	8��]cs:��QY�T�!%$�0�� l�����"?u
�&���BujQ�7��>p{[��$��UR�	b�NM�9�x@������<���_�ᑕM���AȪI.�#c������u#��;�w����F���"�F
�! �v�B����I=�<���t�U0� F4�ʠ#���+�����*����bS?u�ӌ��\_�!H�ƅՌ���Rlr��'��x�zƗ4t[dr��G�(QX�@+G^��WS���mTG����.�(�v���Ks�\�������� ���ٷ����qJj �
VD�$0)�	�^��"e*U8�dρ앇"^\���R�� $�5$�g��aP� f]D�5A-��Q���K?L��;�v��eP�#����7�%ʧ��u/^ ��y
=Pk��g'��:ǅ@�c���w�#CQ
�-4몄,Ǟ���*D�����,�z���Ԓ��T]n ��A��u�U�bc��U�(�h��=&/9B�A���h��<F� DU)��N�@b��}����<gA2�#6��W�l씍 ,�
����ƈ��;u�@p�=��M��������d�9D�#D�C��g������������+>��2�Ύ�;�vŉ~�L�e!��
Rl�d��:�S;b�nT�����D��|-T���,�9�Ho/prƄ��^n;�%���s��: "��� p��`� ��Uj?�(}j��.+��u�cA\�P�J+X�*��f/��N�+���YO�4]��1�����Fd� 0M���\�e��f��
0��M&�J���
�uU>��Rv�s1�k���C(�ؑ���������#I��
�9�է��uDń�7����=��!�����؋��Q`��Eo�C%\En�ڏ�v� 
g>eH���~�|+BZ��VQ#s Q��6
-�wk��j�.;U9�+D*��9�l��Pp90
����(�����h8DC ��B��
�;���U� �4��U�H�c@9P)~�R����}�n,�hn«
gQ"zT�pW�l3J
���!l�B�0x���n���s<��U���������+M��>����ٳ��,1�����\����C��E��$Q25)�\���  ^JG�rr�!!G�	
r�!G�
�����&PB*H�P��G� _ox�5Y$xE\��(!l+�"�L�T�XE�Ǡ����]��҅/��R�P�8�P4���
������ E[r@@C��2�%(�
$)�j�88�j*(@l�f>vsK��Y �� }�T;�����=|��~ v�Ü�ZoJ�bAW-N
�-qRj�������A�ہ�`��|�D�ه�B*� �$h��`	�;3,80�tb@C�Th�8����JB�P{� �M*i��Џ�
:R�	<�L��Eck�H��;��	hv(=�
&R�ult*�NB��\H��&"bB!Z�h
R0E$�0�C:����d���H�j�t3T��G�`3�-
�
3�Q��AB��F۾�M0�A������7�PX ,�C���̦��1�^4��uaT�e!E<��S#�t�`e�r�f{u��/a�*9�Rr���9�	%���� iK�
!@@��RI�I�V�%� 5N�!�3�c�G��m/��N�b�Ks����R����\Dq~�t�\Z�f�Sp$oS�w-�t� �A� �ꡀR� ):`?c1�5
�� ��uC��7�A��u�)"�!�
��!\�k�<���O j�56 ��Hv6@� )���n������j���h�\��uM���A��W�'8l��
%W��Ag���@�XA
"BE��C7%� �^_^�A�+"+t8R@ �C� «XVP��.�q�jR�<g ��J� �u��$!\�)F�!U��� X`jvx���&
�s̨�W�Ȱ%@ʚo��Z& R&8(E�L��ȉM&U�L&�v�\M�&�t4�p
f�K`w����q�$jhT���zc$gkP�lX��*=��;h\�Q�*�h~0�mVU� �@�T�J��h�����El;Mv3E�$:�����{k�a+GE�=g�u����������Ű�`��/$ :L����7t�b��rr��L���.�
d7���>H�>U��ZQU�� �V����"lmX�!�%*��3�H�+�@���� 0�+��/���L��,�}�y|S�4��H�T�s3��[ �R��U�@�@:����}ಟ��Rq
����Fj ��	ɼFP�N�P�����U��6=:@�����REI?pVW?�#3�����+x��j��LljP��A������a$���/�P"!��>�	"��}K4ƲciE�.2�L�.��� ���\����Ԭ�R��&�]��/5Q,U�-9H����씠U�� 9H�̬���Э��h��� ^� 
���;���E�S�0U��"���ڀ���v*��	�$�6�<�6c���+=c���`���A�h iE �MpU��M���i!���(V�P����u	��]F��� �UC@���l	�ؐk+��E������E�ڶ|$|v]�m�M([$��`�Pթā:	����!g�]�;l��ĹG�(�2`��=���V��=�k�7��u��
h7 ����EX��.�'�$C��1W�X<�`C4�$
M�����j��xR�(W/ȐM`$��S ��v��o�,�r$�!J��+����;������nH�QL�D..(�~AP���lsJ*�%�;���xdCb;�(0�F�����gA�P��o��hH��M� 1,/��R��ƃ2%ǃ�(`�~�/m/�.��	��@��|�)�eG��|U�.�,���dl9h���51��B4p�4���w4���,�����d#r"Zq��MrH<1bKd��#�u]@	��@D��5�����@H���lBh{��@��,x>�T� ��4�\b{���mC
��?O ��17�O���UzE@�&��w��b��R&��,!�#�A�:�K�jl�h��-�h @�
c߷ĉN�"�
J���EL1&U
`m'4�RhQ.�}@가�����aݐE#,���;v	��
+@r�ö@[XD\��
���d�J� @z0v!s=e�u�	�r�	�@g9�p�������s-����	u�1��ՋQX��}jQP����~���A�\+E�\k�!#�`22�~�;K�Ж*����m�H�+�=�J*�QfA����9',d(ԡ�.��X�|���	�zC4X�G&�mć
E�M]����"�
p ��v#�f��  F�^��l�U
O��5�xs��y��6�1:6Y:�8�.(#h���m4�@����� ����6��Y��o9V!!M�0�\��G��0E�$HR���k�¢z ���`C��.E� ��|)��.5�|�#�=Q�J�g�x�K1�BŒ�.��A��ԑ4c Zt׵�
�c@��K���2��Z��,F%~ �;�]Da�A�ǂ�g�,��o��M�&9�9 ;�$(�������pC��n}P=p�+$;�}��,, ��A����ʄ]8�̨QK����O m��fU��R��J�����%}� � �dOM� �)�� ��&�M��d��@����`�t`tY�[hTm�7Ҝ�E���<�<�-���X^��аeJ����(�N��M܉u� ?��ڈ�� �)��cAF��hO\��L3�|��n�2|�C9l �I��Ƙ�mx��o@o��lE�/t�+�b��x��������Q�� ����R=O �vd34�� ��hfXl��ie  E��$���ĉ��X������Al*����h`�Qf�j� f�G�F���H���]��
�\� ����1��q�<��{�;�tP�Y+�6"�ĻK�P�^.Q0�"2��$bQp9��F��=S��(�SZ1,
�[��*FqdQʂA�+��� ?hBb)�d�X�w��4IXt%��bt0C��~$^�!x+@�� �X��=zL��,b�L�B�,��L�9a'�_}G+�
��߽ }	2�I�f�%�>j<��<�s�J@�րb��F�-�/ uF=�
ă@����I��x���h�����
�-{�(j����0��� ��64Q��}Y��[,�,:���J�9��LV�+@$"��7rla��>���B��o^��\$?_C��/"6!�0��?h,���;2��N�480V*����`��5�`
c��Y�

u꿍��$_;��u�~��_�a���k���uĊA1�f}׮�t��/4�¯d�G��(��Ë������l5بu
��Ju�v�{�l�L�u��P��vVRH�E4��<#<Y�LWVj �L�Q�Xu�e�-��qQ�nu��n�r�X^-�s�+h���ȋ���?XP-ܠG�WVS���:L�HQ%�
�t!
�FG87�o\8�w��8�	��8���Iu�3�M�	�_��W�V��x��َIv��m,7�( ��l���
rIW%\�ua�soY�B�=I������7�9�!�GA�bw�
�����uһ�g�~�Q¢@��J#^0�J��䫪fj��G�Y_]G SU�`� "�uD 1��(
Y�����][%V�9akxc��u�E�RW���AxY�n
���������ߪm4Y��v�%�9�q`��k��
���O�XY�/���
p��ux��cg�NM4k �cf�"pNCjm��)�w!_L�x����|9���Bѕ;�ON��9Lw)�ڃ.�~����M�4G}�#S &F�1�Ws�};Gw��vGCw��,\A��Uq� �Q�]���x��2aV$K�Խ��t��&���-j�@d�5RB[pADX/q����t.;�$@�4�mCo4���H|��JD�}�+��Q��T��d�� �Fg`���ƾ��=�yhrQ�R9QKp�m4��Q���m�Z?�KCm�[kY[� n�0hXHd�vf������C��e�,�F�muP�R����V� ��k�u��JtF�ƹP
��%�F[/صd]�@+�K�T��#��PNV8W��\�k�6%T	�?���mj�]WЍA��"vk[�
����� �tW�9 OkB_\�sE���Xܖ��]�S��$V|� ZA�5�F1(���+�-�.f~E�����o#C�]`��e�������
"��9����������S�>�+[��ua���Z�u3Cܶ����Y\k`x���--���,?R�����C��t[���A{G�ña#8�a���-Xi��C��Þ&��Z���4B��Q  G��YEt ���Fк7�jèD}7-M�St�苶
���3��Bzk}�Mhr�yX��
V�Ҟ�m7���F@�f&�m�ȦmU �
M!�0�t4$|�J���v�؂p��%^F6N ��f�Y�J�dK�m�N�­�5xU�[k�к�k"� ��f�aY(�Y:�-4�~ppS0#�����S���9�OCVW�F�%}�؁&݋�V+�����	Y�Y8t3w/�o���n�+Њ:˷��}�8x@8�8���ʪ��?0,F�C�V�і~3���? t�΀|l���77��V��7\DC(2���lc|�~��.��I���i{�F?��D0`��l��@Sb�5��� #�+d����Y jx
ƾ�i*A���*��ۦn�t	7<
���;Cx�̓<��' w�C��^_[]��m���1��#�¯�;�t�/�."[�a�PV�W�.c7��S̺ x�j@:A:=â�%G��X/pmGM��G�ߨ�
3� ���G�����8��F&��ſ���	3�F����f9Y�K�u	f��$�X-%]�lGT�V�R��1�'�Nԍa��Mz�U�e�ۀ8_F@��u^ Cz$]�E����c�'"�?C20XC00���ۋ��`�@�#b�4Q��D��e+*!s{�a�vm��%���EVU�kT�{��]^A�3x<%Sm���P�yVQ6�
�"�	h�X! S�U�����_��u�< �z���0XT���D�[��|gt<��{���}%\�-:��'�����)���X�@z5�X|rV��d������&h��M��j������d�>,��3�u,9Xd~��
�)�"�Qp
n�����ȱc�������G\�9�MH}�%��x��S��;�$r
B8�tѱQ[��~u��E������ŏ�
�����3�g����'Zo��3�h�H�ā�u%�������XuĻ�7�C"�B�8�t63�8W�
z�(̅�����ۍ� 8�B<a|Vx�P-A8BAT}{j>��z��d�I��� ��Y|)���O[d�U`�}PBP�-��w>j,
n��Ď�*�t�t�����E�e����Pp���Z�q�Fʋv�T1����s_�L�1�DPu
si�1`t���eD���vV=W{C�'��@�o�~@&��D��؍p�Qm�n�h ��6����%�Ϥ
�v ��C;�ec�F|�D][�'�^j�"~RF��F?��Y�J��6r�Z0�嫯�| �v��Du*�%< <Ǩ�S�8�Hg�jm�0}�@ A������I+P���r H������z�#�Sԋ���-����+y����i�	o����DNI��@�P�!�;1�1�V�ہ�������~����?vU?Z�KS��o�KuL s����������!\�D�	u(�O�F!<!�J� �����s!Y
� ���_�SZ[:w���Z5RԄu
H�yC�`��� ��iRŖ�������)���Ɓ?��x�8���(+�K�Q����Z�x�����s߅@;6<ڃm�H�.R�=�`�|
e��e�%����q���+�{���Ig�鍍�ߗs"�;#+���#��u��;]�r�uy��;ط̈́|&��uYs�t$sW{?)쐯��7&
u#9�t��ʀ@��`HLW�/���fj d_w|ыBW��G�J T_'��i+���N�?~�
�L���-�,�J!Ja���+\' ��|8R�����#\�D��u�%m�E�!��Ou�h�+���R!خ)6u,���`;�Jz+��y��Fsuy���5ӳ�|�"�:�YQ!�m��ȝd�:��6�R�})�� �b�m��ǰ���	;
�	|�v���/(
\ �" 0VW$�n���u0\�PoP	f��<ta��~Y�=Dh�A�ҩ�E0�4��*���3�2Z$nF  �W���\���d�̃[{�Q�>�~�mbA�N�!hm�A��q����� �O�C��x?i��Z�0	(tł�@���ukx�J�����X$bQ�Ë� p(^qpn����� pB�w<�GwH�`�������@���P�B�Hǀ��
�$aN^���K����2?4R��]¾�R���  p�HZH�wY_r	�o@)^E
m�VF��~"S���F���.��7���J#�JE����]Ё�d|�
W�W����;�s���
�B�j���Gj�m$�Ǵ��T'�ߓ��W���t`��o1{X��v2`�{"r�u��	B�u
�
�� ��A���Y]^¡
��V�`o��ן$~H�_��+�~���zv�;��E� �#���;�|9evS&xn�ƂiBu��t,�A�7r�X*4&��~*@c�}��A��*�;�|L\w�"HN&K�E�م��R;;r�Ec7p�6;�t
vo�)�H���xdi� jЃe ��Q��?m���)6h��H�2˲}+������w;�(6���0��t��X�'����PV�^�1Ƨ�H�q�V^�f*-Ѹ�Y~�����dP��!�M�����A�T�f��R{�~�B��6�{!�;�s]�t��
P#��A?�u��;W|�v��7
�+Ȉ�G#�����`se���S�,E�wU��
@o[�u���uB�#��FM;����b+'+sT��[138e@�<^�.ͷFC�cC+u)ւ���d�HPs4��$Ķ<r�^(W���~�D���+�T��4t# �y��t4�PQ;�6�6�c���|�Z�jT794j�ۻ#�RH�<�l�4�_�cu>W+I����T�P9^b�Yz˃PY�
���.�Ы�z��
{ȶ�x����T��%� v�H?}���D��s�4H< sET��.|
���@  7�q`{��;߅�T?ؠR$>HL�Buދ���ۥد���}��9	t${�f5ajW��6WW��uc����$t2�m[,K
4w�gt���e۬�&�(h�U�����qa�
�{ �X#��nԻ�e������*��DJG��e
pK���]	X��v+�	 	��$((t���jbB�^��Ę��*\�<�"W�P�
e�쒁W����ۏW$���"Eů�
�Q[�&"H�;i���%*�����֊�����_"��� ���E���m�D^��jn�J�t��[E���1���`C��N>t^����*�FtT�߾��Lu7�c�E�~6u,����4�#=�EakDB�����E'"��",htl6w���JA��;��"����MM��}�-j�Ԍz	�D@�E�u�㼱�h�c�@E��]h�}㍇<SL<C��6��;���.��w�3��I�n@�t(j�V�c�{�u;D
�[(�cd�� k�jg~8��Zlip�WF������l�m�p�md^���- ~���f���4����w�u��
=�@[Q+�}�mp��W��2�D���TD]�	<E�]�~�t��!��S���Z�!S	wٷW�F]�8D0uf�
2P��F���[��/��I������BNu�2��\�jA��Ћ��D4*�@>�M�u�!�����]��GcB�Bf��%�^t_�5ؿ�Sd�]�Ӈ���
2���7�|_�%7�v�U�����HI1��U���� �(�R
l���?�<����8M. lt7S58�%�ٍ|�݈�(��h��ߋF��x��� �� Yu)]E�U�.�"Up�,c{��a�8���h��8�@��B�p�6��F+|��,�
U��V���'�$>�P�v��}�ub)U[[��T��nq�[��hV�	l�5[0����,�a'%�;h��Q��b
�
�Rٍ�C@��(J�A�ǋp§�QW��"���� �<�S� ��qF^���[�v�@��vf(�Ƿ�ޕN�轗F�F/}�V5�$�f��"�mt�w��u����Uo��Yf�'Wtg�����>+��H9XI���U
��^C��6�,���?��G�MJ��Y�k�@[��tP��%Zh�@ �R?��f:�I��M��j�h�_Ux+�9�_�N ��jsEo�
�au�Т�$!@�h3T-D���n��u	�t������t���N*�
���]Z���+�����`��h�0�u���v�t�LP��
��P,°�*t.��Y�
i�=*X�����X�������W���
��G!%a)�:	L5�RP�� ��Z�rA�c���O�wq;
h
+�	t��(
 ��2�
lK����-
":T|�
���菌��MHU-���r�D�� �� ��Wk�[*�T�S;�w��*�C�A�  ����ɪBB�B�A�5<D�_[��5-��zP�V��m��Y�#�Vl	�V)#���d�h QKo�.\�rMg�j@�[�d�k�sV�h9 �޻[�〠TD[� ��I�r?�Ѣ��KȀ� ���؈�IarzwNvöm��LJ�^n��X�j���XI���̟Z NQpsKVU�ES=�'UOr9U��r`	�G����
u�O����Z�Z���#U'u	�N��
@�F�3B�G���#�0�t
3��W��0{(W5��o��9>t& �'�ft�~�MP[�o�uij#(}�t{��տl7s�VP�8csm�j �Kuxv'GI��?� f����u8�d>2���l�$W�J��]��M.4w@rP[Є�|��f�|�@��ci���Kg~G��%�b9~u]
�A p
x+9tU/~;wۧ`کGn����h�o�����j�ڢYk&��lHT���Q64�Z릉sbR[	ɿ���!D�7ax��S@Bt<w���8W>�xb, ��vuW����aBCs6n��X�>�F�u(@���s(���H�3�H��W�N� !8!.��x�B[���Â��P_ؼ1�-����i��}
}Yx�At�p��"�{S޷�ó�NS�E� �@Ϩ-� "5��		l�N�@+U�/U�ҁJ(��-zŒK����`x���?�Z*(�J#��U�^��0!Hc5O�PW��n�	u7x7�xp*�
2"�EXDs P�AN�0Q+x��	��]�kA`�����|��D�j�x���Y&0<�Ί����8F�[���Q��I�Ri�\�C?+R�]K�H�s� Ң�">�v&����.]�T"�a�K|���� UwH�u:W�t~S��=3~�P_�f���9tV5H��+�Q�;�Ng.`KDjPF"H��1NF�?�Ҥ d<M��W/@N҃�� QQ��
�Ü>�*������6l��;q"���-KF�wXr��r�$wj1����Ҷ[��1�^9�vl��_r�w
��j!S� �>Y
3ɸ�������� �x�y�|�Z����h����!�ɏs4��n�S���
���	 A��,�|ѹ����AE�f��i��j�r_���w+V�����|xLbND�>�}����"Q{,�)h{,h�fy/��s8��T��i�D���W
J��]��9t�t<b�>,�jjK8��Y;�+Y�*��i�0
~K�$3����B�]��P����[d�����qWt����e����k�����uf�f��-���"��*�.����F
���ѩ�S����m̀��7f��1�f�fݐ~&WP;}�V����
G�B�[�ꨯ��h�+c���1.�Y9�~t����"
�]�_�@�2��ɜY��H�h����u�x����C�_��
q!tu�m��A�4�O�JF;s��|�� 9�H��Y�t��Y=�>�mש�#����H����7#�OhW�p�}�,�{�c��A_$�~N��.$�&|YU��6Y3t��?$����B��V��}B�� W���#�T	8Q�#���n�1(���$������@z8N�'�����0
,���	C���:O[h�gj���bF ��'o�j�V�[�V�Ny>6P�����<���3�ɍ<�I;�[c�h�O_��^0��y�Ў�;�~�u?��p/T����HjjD����$�B�	������W
�bS!fҋ��*'��ڡ����k9G�h� ��}��P��.�r`������W�ɉ]�|m��rif�G�BP�
+�+j�Bk�&i�3��U-\$�WYȳp뻅�O�t,��{��4ZA��y��@-�+����X9
u"A��0�ҁ��u��?�Y ���}]����������.U�FS!sr5�+��n�b�H�GW��40�V��|b@P�{�u��6
����^U �dŎ��u���8�@��oG
��[��:A��P`�P�����u��ƐM�����C�z�n��BD�A0���֨3[��[���Ɋ�]sm�r |=����YYG݅`�ץ��XX����<0�n��Mm]���}`�����v�9b<�ń"E=ghS-	�'�P��@��ξh�K|�v�e,0����@1PQj�`�uԊ}0Z���P�����FbǨ���6�Aѻ(:�t�A�n@�M*�,0
�M� ��h��r@�����t�h瓭��kYnD dH���������d(�*�PO9 vo�aLe�$)ޖx�5NP
>"�-���[D������Q�~��'$�Vf�h�Xz�0�4�FS8]���.�$��}�_��P>Oǘ��YY�E��-���

��0�"]"E���w!>� !�oS�d,V;���B���n���3�C#�}���� 0���.��{0U/"��6.0YG��F�H~D��/Y�TvG��[}+d��]��9�|��-��j0���a1n�SV�W{�$;v�p��Qx���)SK �4q�.;���:n}��|&�}"�
�!l�u� G�#]S#�1k��QSR��5}u��021,fu]<��ɲ�]�`A��L��N��!��j(Q?��j��~�@qS���
f^_���P/�L� 6P�tGK ��oC���r-�����Q u�|��$�h��&��&�2�� /#J�N�8�mH[� D�h�k"pԡ�@𣢬ϭ�Ѝ�}T�)���o�����F
�'�� ���$���@�df0�<���Wq�*��
8��;+���W;�|�9=g}(�m���䓱͞!��ύ��Y�����`{�F�@o�$���+�Y�M|��B	�(5LM����8��2`$
.u��h�#x��*(�`	����wZFC;�|

��C���[/|��Dt��aX&��"�7Wx�!� �{�	��D �ǖĤ����$ r��C����%	�D��
$7���;��8"uD|@��"t�1�)��]K��%��;2��F@�����o�$�F@F>C��m�@D���s׊�)e t	�F�[		u̓H�1V�Jf��e ��9"0�gD+@�D�'�R����wq;������"\u_���P�,�UjRҋ}�jߢmxx"��*��Ft��8�������Kh�:��C��\F�E�V�뀧�
h?��#��.(&X����@���0����'a ������I%�hS 4U
rw�x��n��u�g�t1���{!�(X�����$ f1����G�w��Cu�l?�yf9�ۭj�@@�m���+������@�F�]�I�K%n5;�2���j�#UW*�k�%!x�T9�'\Tc]�V�\,�}m�SbL�u�%Z�
V`u��ߥ@
u��+�@jU�'�D�EU�4���\�W=`E����][8ŃLRk*!h�RŇ#��PlYZ�v�r���E��z�;�����AD�r��j��#�;�԰U�l���u�p�ޑ���u�+�\�]Ve���xPmYs��$^��Ye<v)κ�'��
+31�A8@B$����
T�p�
X׉����Āѕ��$�f�Y࣠�(.0
� �q2Axj<Xݖ>c��9u m�(uu���J@ck�\)��`�(i��@]u~�JQ����,�ֶ0p�W*D��vf�+#��V�g��:37�F�Uu6q�1�ouu�ru������*�V
y��[��i�������#@�i�ן�������� ���V� �E �-K:B�.U��$���ʐA���F�s�`Z-�!?�JO�:
��;Њ@�!`8R�w�l�j]��R��^�7����DV�^e� -���E�j�	���
4QPF(f@������kSE
#��"H	DpD�(�˪��ƈ��T��� VEm�]�!��t&��U��3�(��^��6�
)��� 蠧�(��cQ�N�.����~*9e|/
VA�St���Vj	ǂP��H�,u���w)r8�u�P4��*�3�>��lS�j?�����ɤj
q�~ŶA��$BG�.�UL���u	w[��#�� S�����Q	��[�/�P��@sD�m� s����cУ����SԠ������V(��h�+2�u.�~ �?���;F�	)�>�@��mA���6v����H�F��U} ��/�SȀ7@%_�&"
��
t	��^*�� 
�j�h��uY"�S�Az�5��n#]V"��k59EÀ�/�
��^{Xj�X�CY7�2vNZ+�~Pv{��PP��3�~'�As(U,VO��b�	 0F�@�:����4�<�����)0I#�A l8L�h�re��$h |{�[;�~sr��BD'2�|9�blHSY)EiF�<�u��G�U�`��k�6LY���dB�aT[�Ja��+/S� � ��;��'��[�1�L�_7��0Dk�#C-FPYd�F��4�����Nlw0��u��;�7u��?������$���!*JE����TL����Z�;�J_^�!ʞ�%W � �����U}��s���*�q�h�*��F@�bC2�7���Y] ��Ht�#v��w���S�6�V��<U�è��RQ< +�ф9$0�F�h/ѡY�m�$���hu0���JA��U��:6z�~��F]ի�u;�Q|��t$��TA-��E��=�[0��/� <o�v8�<
�:*A�v���- ���F�C�D18l�/�u�}�c
���KWj��t.��{w����n�j\t��@�U����+F�]Y��ep�E�� �VF3A����&�VAa�:Ƌ�
���ye�8��CV|U�no���T9Ĭ�J��M��g;^#�pۢhm�H盧vHP���f(��@�"�9���&�0t
[-�Pt� IOT/R����]��(v�}s#�
fŁ+��b۷������ƶ%M�h϶A�Z|�%��X) 
	�(կ>�yh]��Y9�*QT�	C�Qd���n��H�=ux�ts�F ~tmj��� �u�ү�6A��K����v���XB � b��y�
VP�|�^_ޡf* J�N�a�L��Y)LnQ���7�p�Q�7
����w\ڝM_�+�Z�λ� ��]!CS]�3b�`?LW|8�Y7"�.���+�!��AO@/|t+Am��y��8)�'���[[.^0�4J�ъf~�Ç^�o3�%f��
IOu*���> �����B&~�u&�E� �ۅ^���� �y�>T���[�E�u�[��w!��G�P��G��Ri+OJ�>�<;�6!`�?����Xw^V	�JB�Mv@#�[ђ�/[ܒ;|(R0��d/aM��&�wr7���W�+���!� ��#b�T�5���w0������ZP��u
�T�q�nT\� u@1k܂�}�)� x�-���E3R$J?�%������un �a���$�AC��,�m� X��J�`�/S~��ۡ~�(�7B�70#���AO��-�0Z���u�6���'|�95|
у%d�.qO���ǆ�nF%����@���Bup��X�پ��
�LH"1w����wuB�Ht����Ggj��P� t���g�ۉ�)tP����W�m��T���{t��#��S���`M:���H��H2az�R ���0a���z��@�� = ��r_�j\m( 2Y
&0�B�4�� �5.	QÚ�
�
�HW��((Q�!��ۊh �<	*�R��H�wÃB��E���B�g 	��qz(�� 6.vmFQ�����\�{���)����A2 $P�
����Ä�_�����<{]f3öQp9�t�F�l���J���m�]�
VT��NTuI
�ǥ�I��&���d�=��� �!/"A�q�K�|⇉jаH���T[4�Xi��=���Y�k��� �%����h-��'�^A���@�F�9r�¨����<I��;�s9pA��H�ط�;�tB��^x�a�3jV�[8ˢ>��[��D�8�"F����>Uu�k��WFS��,�=��4�^F����7��}_�f�PpɆJ�
x�Qrs�AL�;=^�
�4�^p� 7�6�Q����5b�.��0�`�����X�c�^�E��x���j���4?�h�
(l�]׉˒�%����_`-�Զ��H.g��+t+�@
�\ˆLm���	�C���Z��\��C��e��hQ3ڏ ڧ���.�Z��2�	_�ZU�1��
g��Z�^hP9��r6� '�p��2DsF�+"��-#EQ��� s��]u`Q�"��8�q�
O����DE�A�tdV@p���e䰄�����2��D\��k���K<{!i	XO	�/0D���*h���Yl�\ltHHZ��"R�X.�%N
O��
����b@��"zV��rG3��*�@����=���Ё�P<¾�Q��qI4p,7�O�>��m}�>8��X9���v�}�|�E����HsE�Q�����EjB {�t���}��@�h3��hPpM([U�Z#	���}�
�+E=�~0��A�U]5�Da�`�Gϸ���S��r�A^=����W}�(W��K�=|u��uղlH��ʕ�#���8{�`x
7l�	H�T���_�t��Zq�Y�
@�V4M�i�ؤ̀|uE�]Ⱦ�7����f��W�"�i�m������t�����XP�M����?�p�E1ЋC-��·%�K�u����[�uf�# 0.�l�Uf�z�If�����D;�u?���VC:@�4!�F.�ܖt�A@^	.H���2#�C]�_#gc�~��h- ��w��jἓ�!�i�����M���et_z�Nf��k�M�v������)��	����9P'�
n� C8~PQ(q+��u`I�+R$�ONVb��:�Ed*j�6dӂ�T0E�7s�-ъNQ�	���A�aG�5�T|0�=���7�H���@f�� *�,�Q�(S�djn�/M%�f۠,��� ��c9�H,�bWI���A��T��L�/`�+X9*!��� �V.��ƻ��Ou�ɀ&�
f% P���%���$��A���K
]W���O���FR��
3�#�#ʁ�ժ���f=�GF�.TG���#�
������
�z�?p�:fĻ�*ZD���V
�[��ƍg9���3�
�����S9�� hܿ9�M�6�X|$����� ����Z�z�I�KG �	h� �F�,O�>�����Q�P��1����%r)�m���u[�VmM���X�����M~%!����ΎE�9�c�
`�`�
 � g �C��S�>c���� inflate 1.3 Copyright����995-8 Mark Adler Gl��_w{
s�x�
��������5l��B�ɻ�@����l�2u\�E�
��|����
��J6`zA��`�U�g��n1y�i����F��a��f���o%6�hR�w�G��"/&����U�;��(���Z�+j�\����1�е���,������[��d�&�c윣ju
�m�	�?6�gr���Kt�J��z��+�{8���Ғ
���
s�l{E!P�: �ڰ[.  <��%,{H�kl�;J��GetLa>Av.6؎�u4GeW�/���d2�RaBoxRKKKjuOrE.U��^� H:ml d �}]�,n  y 3n	Z�/d/ A&���D�ember�NovO
���ho
SeptAH[���f�J��n]8ܞeA�il#ch�B��u{��6n
g_WS����KGC7yC?�_��;3#'daCێ�F^	ThsW���|Tu	MׂkSuC;�{�7/'#�n��S#QNAN -F Ds��FS#*18s��?FMϢ��9眰�������s��� !M9!���|�p'�%D'0�k��PU =Yw(��F��t��h���T���^�]�e�����Fz%�nH�cM����A2>}+p�� RSA1��]GT���	��e��L�7Ҥw�L����A����-����m����"�����0e�B<�<��L����"��#�r���oj�����7�B�m��ase CryptXph P��zvidcv1.L%l�d۷ds= []�������L���������
I���T�k�7
\k�V,��Ww�����,�%ِ��~�+�`�7�)�y��������Xɀ���I^;@��R�)�M�����~���+:�L<Ϋ�JCj�PĘ�=���Vgiă2�Y��'��yI0�S�}���y"d!}LX�i13�� ��;�88��M�n�rI�������<�IW��������igp�_V�r�����h$n���W�rW�����+�q�hR��Zs��i/o��K�o���s5�Z��jU��&x8H����t���a�Y��]�3I@�W�Z�)o�������fT�9������Ɏ?"�c�V���.T� G�����{n����_��f.62?R���1G�P2�x����o����S�,0��Y/P��3�	�W  23��w:15:31007;�g�����������r�����~?�&�����r�oQ�*hw�b[�귝j/p���^��3p��������`b>����Qesٓ>��2[VG����|_�J�5B��Q�������?q����Q�� ��.) �m7(2^����Ƚj����G(TN&׶7��&��?�/��/=��������U���h������v����s���+C��7�NǇm"W7�V,ٙ�Sz;j�(�����R8�t���
�/�ؿ�ي����:����_Sd��?&p^�<�}���_D\��l��h�[���~\y�P�������uG�N�h-jk-es�}�'����k�rbt\ht�����\dr�rs\etc�
��-�u�n��D&+ѽu�N4��_���i���S		y�y����I֊Ј�����h�E;S^Cb��s�8:ڒ:�X&�S���?A(�O߱�J,�Lȿ��׍Ҹ���_���4$"�q���9�Ex�UU���31�2�b��Z��t]�=u�;�x�����9꣜�K8$o�9]����+	���o�ۥ�.��m�#�g)��������P�\�8h㪕y�}�����3�%.Zn~����o ŏ�����Z�|$�R[[^�7]��������G��#�;�n��zi����I��K_K�0D� �xyE�o��o�t�\��b0�j�������\�� g
�da/J�?���1=Q��D�[�/0��ҧi55zOd�������(�H�+�T�T��0��S��/}�/*+�b��vǆ������?�s޷}�����R�c��C�~�_��ꍳ��v��b/7Z`��D��������K����u��ˆ�����6@e���mᡤ��M�h�ؿ����5�
C���C����c�ջM:�r��ι�k��?��V?āf�3�>�� +&}4>�_��:N��6��F�+T^�����h%>;^��p��j|�������Ƃ��������Q꼹�����=�
y��ع�D8X��]�wKDQ��������浀�h=Y��_��K/S�x�#�Q�.#7p��O�^1d�˃I
������"����JR�3W��Ξ�'��B?��.�PQc���������7��"�x�R�����
t�~룭n������槭��s�Xý�[9�U�����n�Ap;7~"Wj|�V!̴��s�L4𿏜����D88���������]��o0���,��-)-?͐�����#�y��� s�~�?���w�R0�j�5Џ3��q�l���4�Iu��h-�����Й�X����z�������w�P���d ��_�1H�[�6��V�� b6".F����0Z��{��fK�T��V�n{�~鼺u;M�o�*���I�- c�,��?F�Q����W��7�J�O�I���f�_���]�s�{�e1����~�\�!��L�_i.���o�0�Ž襼����tne��K�S��a��*+gz.3��m[2FN	����V�d2IG��� �E*d%v/L]�:CDױ_�m�RCS�����������7�H�I�l{�j�_���^�gO��k;#>��Bcv^��;�G�%A�:�Ð���Ė��`^����B�9LS����r5E����:�z�������$ѩF�p'Hn���5g����;A�J�@{����9���Y�T��)5<
F�Z^`���80"^��>ԓb,�\����c9[�ϻQl�~��2��� #��%#�\'��3�/_���n�X:�K�����Ę�5ݺHUdm.8������U����@�.gQ�v�����k*n��-�䦃�:|D��3[������C��\	Check W��?Xj�0[����b�Ȯ��»��J�F���z�Ï�� �SK7N��>�����f
K_����3«.��q'�B��~�[��V��':g+��"�9���ߠ�/ � ���V�Owx��P�{}�<�ώx����!:ԃ-�	{�!J�%�ڳR������|ꈷ�H�.cQ�^7�	�/�	t�i��#g��� J�������r����F�+vK�Φ���$9������R4�,����,'�U�Y��_��{tprefix: �~/$��&\���K�2�Y�I������(#x�w\������g�F�)~云g�n�������[��c�3��'A������(�*��l&CO�B��T9���#�C]U��3KF(`
��;�jC�7|�귷W|�_|��-З'�
^��q�z�@���Х���e3[�2J���+nB�{��
�nk�;�J D����TotalI64uMB, F�e"�{� Qu$M���7DiskSE����xASMemory(Avail�/�m�[/PR)=luKB mﶰ/`Usn%m[�P�� TmP�_�?�No Syst��b��Ze�,h*��Xtus Uh%�� J5�]�%�Off�AC P؅
�er-�P,r��TMSSDFXS{��R	PSE36PATMCA�GPGE
  ��/TRGFp���l�ICGEAM��؛noD VMg�
MW�p���� f�����CMOV(PXCHG8R RDTS��~l3DEw ex2ns�����s'MMXB�w!CLFLUSHc.X�E2��/F8���PU�P�%�5��GHZ/�#RO�B��[COR OBILE;!��*�hs@;��typnppl��6J%uc l	��Zru�fn�EtygAut٫x��ncAMD �Nus�sK� ofF�.C�}5�ou,T()X���LakuageC��f�9��sW;mp�C��mNk3�g���n2X#OS 5�?�-��v.|(Bu'�m�d�)'� (�&�}Ya�kM�J����W� XP+VMS�v�� *i�Ma� kU���l��Q�
�>bifyyCtx��V�E&s�րB6E�>~��98� ���98��R2  z�6�CCERV��o!�T�LANM����WI�d�T�8�ƿ+YSTEM\CFr����.�ol�\
\,߰�Op�rT 4.0 jm!`}E-
	@?�E��F�d�n�A�a�po�Z�n2Ce4/���rdAo�Xb gK9�d�c�fn$l��al8H�eW�ml�k�a̘{��+HSVi'�`�03��pCZ(3+/+ ��2K ���@��4����7c�!r��ڗbuff���͙��Di�pJ� d#!�l�4�8m/*6���lew7e�F*�2�aCW0������Rk� �   *5��yMǻ4l�	t OP�`w�te��oo�HyorZ-�F�syG}s?�9�F�/8��s {�u�jC'$�dy�
�7ic~L�$v���o?ubsiMed'e�=�y��-� with�o����(g	;>!��B6?#�� �KƯ�a �cf �V�
�S�g�`�y�!/����}7����@~��/��ڣ 9;�o��@�����/A�/Ϣc���� ��[_~�	Q�췻�^�__�j�2/�������1~9�;V��7����>
�6;W�`[ �
�y���	�
'O�<�\,y����|�G�<D�xy����y�z�����n4G{*  �L2e( �L$H �d�.,!��X?���; J$�   `��/�
ףp�?Zd;�����O��n��?��,e�X���?�#�GG�ŧ�?����@��il��7��?3=�Bz�Ք���?����a�w����̫�?/L[�Mľ����?��S;uD����?�g������9E��ϔ?$#�⼺;1a�z?aUY�~�S|�_?������/�����D#?��9�'��*?}�������d|F��U>c{�#Tw����=��:zc%C1��<!������8�G�� ��;܈X��ㆦ;ƄEB��u7�����.:3q�#�2�I�Z9����Wڥ����2�h������R�DY�,%I�-64OS��k%�Y����}���������ZW�<�P�"NKeb�����}-ޟ��K@��ݦ�
 ���*�JFUQU%�ɨ���dTUUU2���*JFU���!�9�ح;�@�� Qm�
	tt�nۀzuslsc�� �at	�(QkA4C1���AExit
�E1s،�f'9�lR T���0v�zUnhyd ΰ�SD�ter�����A��mf�w�ck���@��upl
��l;�T�4EuX���yAll�K6V�	S-7OEMCPOj��	A�LC�%��Map�W
5�Rtla%��wind"ԬK�/�omm�ne%) %4�/X�X'Zo��-���_able��lC��oyq�fI`�+F�`V�ai�� C�r�{�`M�By>
0�%���� 
��PzDIz�tKey��pk��as�1x#��ڛgOp� �#����c���
��,�Dep
�
��^°rg�ureg`�`7g �a�Acquir�A{�#2H+���EQ�s�sDD�8��us��T1lo/흌�%DC!I��ct�
@�6B_^{v�>BltQBkO 
�B��f��Zw7�sA���!ag�ν`F�!t���"�Aei��!�cp���ʰtfPx0����eD��2�;#6BA(��pF�Ɩ"�@Fؑt2O0�l�A�E�YCa;{,�.ck�)@�	

�j=4�}  -# 9����'	d�k%*l3.�n��i
	4**M������


�)
���	��,{�ff�	o�
��a	NM��{W	|<G۶�7
"���}�#x5c&R��ڶls�

 v@	(CC{3%�a�&F��.|(T+�HE�:�ǔ�0�
oeu�w�$B1:B�_����>/1,.@�d05t��I3�^�ݻ
.��m�>
w��q��a)�o?9n���]}�k�uO��w��K��Q�X��Z��F��o�َ�1�ﻏT&-�6-w+S
< I�{��GE�Xz
�	lz
6�'!�7Z��bT(.��	�D`
�X&t�.W� \1�F���!)�5�/��YB���c[���N^SB�ZW�:l>&�	�`K&��)	������p/�vAi�1iH=\���+��^�(�� 7'N,�Y����-/�MCI^hV��7q���

sC\�B�38�	o7h��R8�8n