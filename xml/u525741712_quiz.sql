
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-02-2017 a las 19:22:15
-- Versión del servidor: 10.0.28-MariaDB
-- Versión de PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `u525741712_quiz`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `description` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`description`, `id`) VALUES
('Imagen de interés', 1),
('Imagen para enseñanza', 2),
('Imagen para investigación/publicación', 3),
('Imagen para discutir', 4),
('Imagen para conferencia/sesión', 5),
('Imagen charla congreso', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lookup`
--

CREATE TABLE IF NOT EXISTS `lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_original` varchar(25) NOT NULL,
  `word_result` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `word_original` (`word_original`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `lookup`
--

INSERT INTO `lookup` (`id`, `word_original`, `word_result`) VALUES
(1, 'izq', 'izquierda'),
(2, 'der', 'derecha'),
(3, 'dch', 'derecha'),
(4, 'sdrc', 'síndrome de dolor regional complejo'),
(5, 'sudeck', 'síndrome de dolor regional complejo (atrofia ósea de Sudëck)'),
(6, 'ptr', 'prótesis total de rodilla'),
(7, 'ptc', 'prótesis total de cadera'),
(8, 'mmss', 'miembro superior'),
(9, 'mmii', 'miembro inferior'),
(10, 'dcho', 'derecho'),
(11, 'dcha', 'derecha'),
(12, 'rmn', 'RMN'),
(13, 'l5', 'L5'),
(14, 'l1', 'L1'),
(15, 'l2', 'L2'),
(16, 'l3', 'L3'),
(17, 'tto', 'tratamiento'),
(18, 'izqdo', 'izquierdo'),
(19, 'izqda', 'izquierda'),
(20, 'pral', 'principalmente'),
(21, 'aprox', 'aproximadamente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `dia` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `contenido` varchar(5000) NOT NULL,
  `correcta` int(11) DEFAULT NULL,
  `solucion` varchar(3000) DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_key` (`categoria_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `quiz`
--

INSERT INTO `quiz` (`id`, `categoria_id`, `dia`, `title`, `subtitle`, `contenido`, `correcta`, `solucion`, `icon`) VALUES
(1, 2, '2016-09-17', 'Esta gammagrafía no es tecnicamente correcta.', '¿Sabrías cual es la posible causa del error?', '<form method="post"><table><tr><td><input type="radio" name="question" value="1" /></td><td>Adquirida demasiado pronto.</td></tr><tr><td><input type="radio" name="question" value="2" /></td>\r\n    <td>Los hallazgos se encuentran en el lado medial de la rodilla y la imagen se adquirió en proyección lateral.</td></tr><tr><td><input type="radio" name="question" value="3" /></td><td>El paciente se movió durante la adquisición.</td></tr><tr><td><input type="radio" name="question" value="4" /></td><td>La imagen es correcta y no hay ningún motivo de error.</td></tr><tr><td><input type="radio" name="question" value="5" /></td><td>La imagen es correcta pero es muy feita.</td></tr><input type="hidden" name="solucion" value="2" /></table></form>', 2, '<h2>La respuesta elegida es la correcta porque:</h2><p>La imagen muestra "<i>aparentemente</i>" dos manos derechas lo que se debe a que el paciente se movió durante la adquisición.</p><hr/><h2>Posible solución(es):</h2><ul><li>Asegurarse, <strong>mediante una adecuada supervisión</strong> de que el paciente no se mueva durante la adquisición.</li><li>Usar sistemas de retención adecuados como cintas con velcro, etc.</li><li>Poner peso sobre las manos, p.ej.: un mandil plomado</li></ul>', 'DEMO/ICON.JPEG'),
(2, 1, '2016-09-19', 'Imagen muy interesante', 'Prótesis metacarpo-falángica 4º dedo de mano derecha', '<ul><li>Las imágenes observadas corresponden a un paciente con una prótesis metacarpo-falángica implantada un año antes. Dado que persisten las molestias se indica una gammagrafía osteo-articular.</li><li>Las gammagrafía muestra captación muy aumentada en ambos extremos de la prótesis que es inusual transcurrido este plazo de tiempo, lo que, sugeriría movilización de la misma.</li><li>El motivo de la movilización puede ser mecánica -por sobrecarga o aflojamiento- o infecciosa.</li><li>Ante la sospecha de una causa infecciosa se decide realizar un estudio con <strong>leucocitos marcados</strong>.</li><li>El estudio con leucocitos marcados muestra un mínimo depósito leucocitario en la región protésica.</li><li>Este resultado <strong>descarta con alta probabilidad</strong>, la existencia de una causa infecciosa para estos hallazgos.</li></ul>', 0, '', 'LMPH/ICON.JPEG'),
(3, 1, '2016-09-20', 'Hallazgo casual', 'Hallazgo casual en la imagen whole body del paciente', '<p>Paciente que acude para valoración de persistencia de molestias en el tobillo tras sufrir una fractura 1 año antes. Se sospecha un retraso en su consolidación.<ul><li>Al adquirir la <a href="javascript:markClick(1)">imagen de cuerpo completo</a> se observa un área de captación no esperada en cráneo de intensidad alta.</li><li>La <a href="javascript:markClick(2)">proyección lateral</a> permite confirmar la localización intracraneal del hallazgo.</li><li><strong>Muy acertadamente</strong> el técnico decide obtener un estudio tomográfico -SPECT- de cráneo para una valoración precisa del hallazgo.</li><li>El <a href="javascript:markClick(3)">SPECT</a> muestra como el área de hipercaptación incluye la escama del hueso temporal y se extiende hacia la base del cráneo afectando al hueso esfenoides -incluyendo, probablemente- a la silla turca.</li><li>Este tipo de hallazgos puede deberse a:<ul><li>una lesión neoformativa ósea primaria.</li><li>una lesión neoformativa de carácter biológico menos agresiva, tal como, la histiocitosis X</li><li>una lesión ósea neoformativa secundaria -metástasis-</li><li>un proceso infeccioso óseo extenso</li><li>Una <a href="https://medlineplus.gov/ency/article/000672.htm">otitis externa maligna</a> avanzada</li><li>una <a href="https://medlineplus.gov/spanish/ency/article/000414.htm">enfermedad de Paget monostótico</a></li></ul><li>Las dos primeras opciones son posibles. La ausencia actual de clínica podría deberse a su crecimiento lento -improbable para un tumor óseo primario pero probable para una <a href="https://medlineplus.gov/ency/article/000068.htm">histiocitosis X</a>-</li><li>La osteomielitis sería muy improbable ante la ausencia de clínica</li><li>Por último, el Paget es una opción posible</li><li>Se recomienda la obtención de otras técnicas de imagen para su valoración definitiva.</li></ul>', NULL, NULL, 'AMOTR/ICON.JPEG'),
(4, 2, '2016-09-21', 'Imagen de interés', 'Síndrome de dolor regional complejo', '<p>Paciente que acude por persistencia de molestias en la extremidad inferior izquierda -pie, tobillo y rodilla- tras 7 meses de sufrir una fractura conminuta de tibia y peroné tratada mediante osteosíntesis (<a href="javascript:markClick(7)">clavo endomedular encerrojado tibial y placa con tornillos en peroné</a>).</p><p>Presenta cambios distróficos desde hace algunos meses consistentes en <a href="javascript:markClick(1)">cambios de coloración</a> y <a href="javascript:markClick(2)">aumento del vello</a>. Se sospecha <a href="https://www.google.es/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwit-pzI4qvPAhUHIsAKHfXFATwQFggfMAA&url=http%3A%2F%2Fthblack.com%2Flinks%2Frsd%2FCRPS-guidlines-4th-ed-2013-PM-50p.pdf&usg=AFQjCNF8kZvF8HNwa9z2hjxi1qhCnsXXJA&sig2=Zd2aB46qKQyxW7rTsbSfkw&bvm=bv.133700528,d.ZGg" target="_blank">SÍNDROME DE DOLOR REGIONAL COMPLEJO tipo I</a>.</p><ul><li><a href="javascript:markClick(7)">La imagen radiográfica</a> muestra la correcta situación del material de osteosíntesis.</li><li>La <a href="javascript:markClick(3)">imagen en modo cine de la fase angiográfica</a> confirma la presencia de vascularización aumentada en la extremidad afecta.</li><li>Las imágenes de <i><a href="javascript:markClick(4)">pool</a></i> muestran aumento de éste en toda la extremidad.</li><li>Las <a href="javascript:markClick(5)">imágenes en fase ósea</a> confirman el aumento de captación de trazador periarticular en todas las articulaciones de la EEII izquierda.</li><li>La <a href="javascript:markClick(8)">radiografía simple del pie</a> muestra una severa osteoporosis consistente con el diagnóstico.</li></ul><p>De acuerdo a los hallazgos clínicos y de imagen se confirma la sospecha clínica de SDRC post-traumático -tipo I-.</p>', NULL, NULL, 'JCCN/ICON.JPEG'),
(5, 1, '2016-09-26', 'Lumbalgia rebelde', 'Posible fractura de lámina vertebral lumbar L1', '<p>Paciente con lumbalgia rebelde a tratamiento tras sobreesfuerzo. Boxeador habitual. Episodio similar un año antes que cedió con tratamiento RHB.</p><ul><li>En la <a href="javascript:markClick(1);">imagen posterior de columna lumbar en fase ósea</a> se objetivan dos áreas focales de captación intensas. La de mayor extensión se sitúa en el <strong>margen derecho de L1</strong>.</li><li>La <a href="javascript:markClick(2);">imagen de fase vascular</a> muestra aumento del pool en la misma región, lo que, indica la presencia de <strong>actividad inflamatoria actual y/o su carácter subagudo</strong>.</li><li>Se observa en la <a href="javascript:markClick(3);">imagen de <i>fusión</i></a> la coincidencia en localización entre el hallazgo en fase ósea y en fase vascular.</li><li>El <a href="javascript:markClick(4);">estudio tomográfico</a> muestra como el hallazgo descrito se sitúa, en el plano transverso, en la lámina vertebral.</li><li>En la proyección sagital, el hallazgo se sitúa paralelo al cuerpo vertebral, lo que, hace improbable que corresponda anatomicamente a las articulaciones interapofisarias.</li><li>Dada su situación, el diagnóstico más probable sería una <strong>fractura de esfuerzo de la lámina vertebral de L1</strong>.</li><li>Se recomienda su valoración mediante estudio TAC.</li>', NULL, '', 'DLHMA/ICON.JPEG'),
(6, 1, '2016-12-01', 'Fractura 5º metatarsiano', 'Hallazgo inusual en tobillo', '', NULL, NULL, 'LAF/ICONO.JPG'),
(10, 1, '2016-12-05', 'Enfermedad de Paget', 'Hallazgo inesperado en paciente con omalgia', '<p>Paciente que acude para valoración de omalgia intensa y dificultad severa para caminar.</p><p>En el <a href="javascript:markClick(1)">rastreo óseo</a> se evidencia:<ul><li>captación generalizada y de muy alta intensidad en la clavícula derecha</li><li>captación aumentada generalizada y de intensidad moderada a alta en la hemipelvis homolateral</li><li>captación aumentada de intensidad moderada en los tercios proximal y medio de fémur homolateral con aparente engrosamiento cortical y cierta deformidad</li></ul><p>Se observa, además, aumento focal de actividad en el cóndilo medial de fémur izquierdo (motivo de la solicitud).</p><p>Las imágenes estáticas de rodillas confirman la presencia de aumento focal de actividad de intensidad alta en la superficie articular del cóndilo femoral medial que se extiende con menor intensidad al resto de éste y discreto aumento generalizado en la rodilla.</p><p>La imagen de fase vascular muestra aumento del pool en la misma localización con igual intensidad y distribución.</p><p>Los hallazgos observados en la imagen de cuerpo completo son altamente sugestivo de <a href="https://medlineplus.gov/spanish/ency/article/000414.htm">enfermedad de Paget</a> poliostótico.</p><p>No parece probable que los  hallazgos observados en la rodilla izquierda se deban a la misma causa, siendo más probable y en orden de probabilidad: <ul><li>osteonecrosis del cóndilo</li><li>osteoporosis focal</li><li>fractura subcondral</li></p>', NULL, NULL, 'VV/ICONO.JPEG'),
(19, 1, '2016-12-13', 'Sospecha de osteomielitis', '', '<p>Paciente con antecedentes personales de <i>polifracturado</i> con tracción esquelética en fémur izquierdo hace <strong>18 años</strong>.</p><p>Hace 2 años nuevo episodio traumático por precipitación con resultado de contusión costal derecha y fracturas de calcáneo derecho y metatarsianos de pie izquierdo.>/p><p>En la actualidad consulta por:<ul><li> gonalgia izquierda de 1 mes de evolución,</li><li><i>flemón subcutáneo</i> en cara postero-lateral de tercio distal de muslo sin fluctuación y,</li><li>herida de localización inferior a ésta por donde secretó líquido seropurulento.</li</ul>.<p>La sospecha clínica es reagudización de osteomielitis femoral izquierda.</p>\r\n', NULL, NULL, 'JATG/ICONO.JPEG'),
(12, 1, '2016-12-12', 'Posicionamiento erróneo', 'Se presenta un caso de posicionamiento erróneo', '<ul><li>La imagen en <a href="javascript:markClick(1)">fase vascular de cintura escapular -hombros-</a> está correctamente posicionada</li><li>La imagen en <a href="javascript:markClick(2)">fase ósea</a> está claramente girada hacia la derecha.</li><li>La imagen en <a <a="" href="javascript:markClick(3)">fase vascular de manos</a> está bien posicionada, aunque, los dedos deberían estar un poco más separados y, tal vez, podría haber adelantado un poco las manos -salvo que hubiera extravasación-</li><li>La imagen de <a <a="" href="javascript:markClick(4)">fase ósea</a> está posicionada de forma diferente ya que tiene los dedos separados (sería la más correcta).</li></ul>', NULL, NULL, 'EMRG/ICONO.JPEG'),
(13, 1, '2016-12-01', 'SDRC', 'Hallazgo inusual en tobillo', '', NULL, NULL, 'LAF/ICONO.JPEG'),
(14, 1, '2016-12-13', 'OSTEOMIELITIS EN RODILLA ', 'HALLAZGOS DE DIFERENTES LESIONES', 'Paciente el cual hace 18 años se fracturó fémur izquierdo. operado y tratado con tracción.\r\nHace unos 2 meses en cara postero lateral de la pierna izquierda, donde tenía una incisión, aparición de un pequeño bulto (como un pelo enconado)\r\nAl cabo del mes, después de una larga caminata, le aumento ese bulto y después se abrió, expulsando líquido purulento\r\nEl médico sospecha osteomielitiS.\r\nDespués de la toma de imágenes en fase vascular y osea de la zona afecta, se realiza la gamma del cuerpo completo, hallando diferentes lesiones por caidas hace dos años, con lesiones en costilla, calcaneo derecho y metatarsiano izquierdo. No refiere dolor en las zonas descritas\r\nA mi juicio observo diferente actividad en los dos miembros y un aumento de captación el tobillo de la pierna afecta. Según el paciente, sufrió algunos de los síntomas, hace 18 años, del SDRC, de los cuales desaparecieron al año y medio aproximado.\r\n', NULL, NULL, 'JATG/ICONO.JPEG'),
(22, 1, '2017-02-03', 'Hallazgo inusual', 'Captación extraósea próxima a tercio medio de diáfisis femoral', '<ul><li>Se observan áreas de captación de trazador, de intensidad moderada, en la región posterior del tercio medio de ambos fémures que se extienden fuera de los límites óseos.</li><li>Las áreas de captación parecen iniciarse en la diáfisis femoral y se extienden caudalmente adoptando una forma fusiforme que corresponde, probablemente, a captación muscular.</li><li>Por la forma y disposición la captación parece corresponder a la cabeza corta del <a href="javascript:markClick(5);">biceps femoral</a></ul>', NULL, NULL, 'PDP/ICONO.JPEG'),
(24, 1, '2017-02-03', 'Contusión ósea', 'Monoartritis post-traumática tras contusión', '<ul>\r\n<li>Paciente que sufrió una contusión al golpear con la mano una superficie dura que le ocasionó un dolor agudo en la base del 3º dedo de la mano izquierda.</li>\r\n<li>Tras algunas semanas el dolor ha empeorado y nota hinchazón en la base del dedo.</li>\r\n</ul>\r\n<hr>\r\n<ul>\r\n<li>La imagen de <a href="javascript:marckClick(1)">fase ósea</a> muestra aumento moderado de actividad en la cabeza del 3º metacarpiano y aumento focal de mínima extensión en la base de la falange proximal adyacente.</li>\r\n<li>La imagen de <a href="javascript:marckClick(2)">fase vascular</a> muestra aumento del pool en la misma localización pero de mayor extensión, lo que, indicaría la presencia de un gran componente inflamatorio más extenso que el daño óseo observado.</li>\r\n</ul>\r\n<p><b>El resultado es sugestivo de monoartritis post-traumática.</b></p>', NULL, NULL, 'PAMD/ICONO.JPEG'),
(27, 1, '2017-02-14', 'SINDROME DISTROFICO REGIONAL COMPLEJO', 'Imagen típica', 'Paciente que acude tras sufrir una fractura en tercio distal de tibia perone de extremidad izquierda, en noviembre de 2016. \r\nSe le realiza intervención quirurgica al día siguiente con fijación, y colocación de ferula durante mes y medio.\r\nA la hora de rehabilitación, su médico le comenta que la férula estaba mal puesta, y después de una entrevista concluye que puede tener un SDRC.\r\nSegún entrevista realizada en el servicio de Medicina Nuclear, tiene síntomas característicos de SDRC.\r\nSe realiza protocolo de adquisición de imágenes para estos casos, dando, en mi opinión, imágenes típicas de esta enfermedad.', NULL, NULL, 'FNL/ICONO.JPEG'),
(28, 1, '2017-02-07', 'Incidentaloma en fase vascular', 'Hallazgo de dudosa significación en el hemitórax en fase vascular', '<ul>\r\n<li>Paciente que acude por molestias en hemitórax izquierdo que sugieren osteocondritis condro-esternal.</li>\r\n<li>Al valorar las imágenes de <a href="javascript:markClick(3);">fase ósea</a> no se objetivaron hallazgos significativos en arcadas costales, uniones condro-costales o condroesternales.</li>\r\n<li>En la imagen anterior de tórax en <a href="javascript:markClick(1);">fase vascular</a> se observó un área focal de aumento del pool redondeada, no bien definida y de intensidad leve en el hemitórax izquierdo.</li>\r\n<li>En la <a href="javascript:markClick(2);">imagen lateral</a> en fase vascular parece que el hallazgo se sitúa en el espesor del tejido mamario.</li>\r\n<li>Aun cuando, el hallazgo no es concluyente al no observarse en la fase ósea del estudio puede tratarse de una lesión de partes blandas altamente vascularizada ya sea en tejido mamario -lo más probable- o en parénquima pulmonar, por lo que se aconseja su valoración mediante otras técnicas de imagen.</li>\r\n</ul>', NULL, NULL, 'ATR/ICONO.JPEG'),
(31, 3, '2017-02-17', 'SDRC tipo II', 'Lesión traumática del nervio cubital', '<p>Paciente hombre de 25 años de edad policontusionado que, tras una evolución satisfactoria, persisten signos de neuropatía cubital en el codo e inestabilidad medio-carpiana izquierdas.</p>\r\n<ul>\r\n<li>La imagen gammagráfica en <a href="javascript:markClick(1);">fase ósea</a> de ambas manos en proyección palmar muestra:</li>\r\n<ol><li>captación patológica en el margen cubital del carpo, con áreas focales de intensidad alta en la epífisis distal cubital y región de huesos piramidal-pisiforme adyacentes.</li><li>aumento generalizado de actividad, en el quinto dedo de la misma mano y</li><li>aumento leve amoderado de captación en las articulaciones metacarpofalángicas de los dedos 4º á 2º.</li></ol>\r\n<li>La imagen de <a href="javascript:markClick(2);">fase vascular</a> muestra aumento generalizado del pool en el quinto dedo de la misma mano</li></ul>\r\n<p><b>Los hallazgos descritos sugieren la existencia de una lesión ósteoarticular benigna en el margen cubital del carpo izquierdo y un posible síndrome de dolor regional complejo de tipo II limitado al quinto dedo de la misma mano que corresponde -parcialmente- al territorio del <a href="javascript:markClick(3);">nervio cubital</a></b></p>', NULL, NULL, 'CMTR/ICONO.JPEG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_images`
--

CREATE TABLE IF NOT EXISTS `quiz_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_key_1` (`quiz_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=129 ;

--
-- Volcado de datos para la tabla `quiz_images`
--

INSERT INTO `quiz_images` (`id`, `description`, `url`, `quiz_id`) VALUES
(1, 'ROD ANT - FASE OSEA', 'DEMO/FASE OSEA-1.0001.jpeg', 1),
(2, 'ROD ANT - FASE VASCULAR', 'DEMO/FASE OSEA-1.0002.jpeg', 1),
(3, 'ROD LAT - FASE OSEA', 'DEMO/FASE OSEA-1.0003.jpeg', 1),
(4, 'WB - FASE OSEA', 'DEMO/WB-1.0001.jpeg', 1),
(5, 'MANOS PALMAR - FASE VASCULAR', 'LMPH/PALMAR_VASC.jpeg', 2),
(6, 'MANOS DORSAL - FASE VASCULAR', 'LMPH/DORSAL_VASC.jpeg', 2),
(7, 'MANOS PALMAR - FASE OSEA', 'LMPH/PALMAR_OSEA.jpeg', 2),
(8, 'MANOS DORSAL - FASE OSEA', 'LMPH/DORSAL_OSEA.jpeg', 2),
(9, 'LEUCOCITOS MARCADOS 1 HORA', 'LMPH/LEUCOS_1H.jpeg', 2),
(10, 'LEUCOCITOS MARCADOS 4 HORAS', 'LMPH/LEUCOS_4H.jpeg', 2),
(11, 'CUERPO COMPLETO ANTERIOR', 'AMOTR/WB_ANT.JPEG', 3),
(12, 'CRANEO LAT DCH / LAT IZQ', 'AMOTR/CRANEO.JPEG', 3),
(13, 'SPECT REPORT', 'AMOTR/SPECT.JPEG', 3),
(14, 'SPECT CINE DISPLAY', 'AMOTR/SPECT.MP4', 3),
(15, 'HALLAZGO CLINICO: ENROJECIMIENTO DE LA PIEL', 'JCCN/SDRC_1.JPEG', 4),
(16, 'HALLAZGO CLÍNICO: AUMENTO DEL VELLO', 'JCCN/SDRC_2.JPEG', 4),
(17, 'ANGIOGAMMAGRAFIA ANT', 'JCCN/ANGIO.MP4', 4),
(18, 'FASE DE POOL ANT/POST', 'JCCN/POOL.JPEG', 4),
(19, 'RASTREO FASE OSEA ANT/POST', 'JCCN/WB-OSEA.JPEG', 4),
(20, 'MIP 3D', 'JCCN/MIP.MP4', 4),
(21, 'COLUMNA LUMBAR/PELVIS FASE ÓSEA', 'DLHMA/COL LUMBAR OSEA.JPEG', 5),
(22, 'COLUMNA LUMBAR/PELVIS FASE VASCULAR', 'DLHMA/COL LUMBAR VASCULAR.JPEG', 5),
(23, 'IMAGEN DE FUSIÓN VASCULAR/OSEA', 'DLHMA/FUSION VASC-OSEA.MP4', 5),
(24, 'SPECT LUMBAR', 'DLHMA/SPECT.JPEG', 5),
(25, 'RX LATERAL PIERNA/PIE', 'JCCN/RX.JPEG', 4),
(26, 'RX PIE IZQ', 'JCCN/PIE.JPEG', 4),
(27, 'PIE PLANTAR EN FASE VASCULAR', 'LAF/PIES PLANTAR VASC.JPG', 6),
(28, 'PIE PLANTAR EN FASE VASCULAR', 'LAF/PIES PLANTAR VASC.JPG', 6),
(29, 'PIES PLANTARES EN FASE OSEA', 'LAF/PIES PLANTAR OSEO.JPG', 6),
(30, 'PIES PLANTARES EN FASE OSEA', 'LAF/PIES PLANTAR OSEO.JPG', 6),
(45, 'ROD ANT FASE VASCULAR', 'VV/ROD ANT FASE VASCULAR.JPEG', 10),
(46, 'ROD POS FASE VASCULAR', 'VV/ROD POS FASE VASCULAR.JPEG', 10),
(47, 'ROD LAT FASE VASCULAR', 'VV/ROD LAT FASE VASCULAR.JPEG', 10),
(48, 'ROD ANT FASE OSEA', 'VV/ROD ANT FASE OSEA.JPEG', 10),
(49, 'ROD POS FASE OSEA', 'VV/ROD POS FASE OSEA.JPEG', 10),
(50, 'ROD LAT FASE OSEA', 'VV/ROD LAT FASE OSEA.JPEG', 10),
(51, 'WB ANT POS', 'VV/WB ANT POS.JPEG', 10),
(80, 'ROD', 'JATG/ROD. DCH', 19),
(81, 'ROD', 'JATG/ROD. IZQ', 19),
(78, 'ROD', 'JATG/ROD. DCH', 19),
(79, 'ROD', 'JATG/ROD. IZQ', 19),
(74, 'ROD', 'JATG/ROD. ANT', 19),
(75, 'ROD', 'JATG/ROD. POS', 19),
(76, 'ROD', 'JATG/ROD. ANT', 19),
(77, 'ROD', 'JATG/ROD. POS', 19),
(56, 'HOMBROS ANT FASE VASCULAR', 'EMRG/HOMBROS ANT FASE VASCULAR.JPEG', 12),
(57, 'HOMBROS ANT FASE OSEA', 'EMRG/HOMBROS ANT FASE OSEA.JPEG', 12),
(58, 'MANOS PALMAR FASE VASCULAR', 'EMRG/MANOS PALMAR FASE VASCULAR.JPEG', 12),
(59, 'MANOS PALMAR FASE OSEA', 'EMRG/MANOS PALMAR FASE OSEA.JPEG', 12),
(60, 'WB VASCULAR ANT', 'LAF/WB VASCULAR ANT.JPEG', 13),
(61, 'PIES PLANTAR VASCULAR', 'LAF/PIES PLANTAR VASCULAR.JPEG', 13),
(62, 'WB ANT OSEO', 'LAF/WB ANT OSEO.JPEG', 13),
(63, 'PIES PLANTAR OSEO', 'LAF/PIES PLANTAR OSEO.JPEG', 13),
(64, 'ROD ANT VASC', 'JATG/ROD ANT VASC.JPEG', 14),
(65, 'ROD LAT IZQ VASC', 'JATG/ROD LAT IZQ VASC.JPEG', 14),
(66, 'ROD ANT OSEO', 'JATG/ROD ANT OSEO.JPEG', 14),
(67, 'ROD LAT IZQ OSEA', 'JATG/ROD LAT IZQ OSEA.JPEG', 14),
(68, 'WB ANT OSEO', 'JATG/WB ANT OSEO.JPEG', 14),
(69, 'WB POS OSEO', 'JATG/WB POS OSEO.JPEG', 14),
(82, 'RASTREO OSEO', 'JATG/RASTREO OSEO.JPEG', 19),
(89, 'RASTREO OSEO POS', 'PDP/RASTREO OSEO POS.JPEG', 22),
(88, 'RASTREO VASCULAR ANT', 'PDP/RASTREO VASCULAR ANT.JPEG', 22),
(90, 'RECONSTRUCCION SPECT', 'PDP/RECONSTRUCCION SPECT.JPEG', 22),
(91, 'SAGITAL', 'PDP/SAGITAL.JPEG', 22),
(92, 'BICEPS FEMORIS', 'PDP/BICEPS.JPEG', 22),
(97, 'MANOS DORSAL OSEA', 'PAMD/MANOS DORSAL OSEA.JPEG', 24),
(96, 'MANOS DORSAL VASCULAR', 'PAMD/MANOS DORSAL VASCULAR.JPEG', 24),
(116, 'TORAX ANT VASCULAR', 'ATR/TORAX ANT VASCULAR.JPEG', 28),
(114, 'FO RASTREO POS', 'FNL/FO RASTREO POS.JPEG', 27),
(115, 'FO PIES PLANTARES', 'FNL/FO PIES PLANTARES.JPEG', 27),
(113, 'FO RASTREO ANT', 'FNL/FO RASTREO ANT.JPEG', 27),
(112, 'FV PIES PLANTARES', 'FNL/FV PIES PLANTARES.JPEG', 27),
(111, 'FV MMII POS', 'FNL/FV MMII POS.JPEG', 27),
(110, 'FV MMII ANT', 'FNL/FV MMII ANT.JPEG', 27),
(117, 'TORAX LAT IZQ VASCULAR', 'ATR/TORAX LAT IZQ VASCULAR.JPEG', 28),
(118, 'TORAX ANT OSEA', 'ATR/TORAX ANT OSEA.JPEG', 28),
(119, 'TORAX LAT IZQ OSEA', 'ATR/TORAX LAT IZQ OSEA.JPEG', 28),
(128, 'TERRITORIO DEL NERVIO CUBITAL', 'CMTR/TERRITORIO DEL NERVIO CUBITAL.JPEG', 31),
(127, 'MANOS DORSAL VASCULAR', 'CMTR/MANOS DORSAL VASCULAR.JPEG', 31),
(126, 'MANOS DORSAL OSEA', 'CMTR/MANOS DORSAL OSEA.JPEG', 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_tags`
--

CREATE TABLE IF NOT EXISTS `quiz_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_key` (`quiz_id`),
  KEY `tags_key` (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `quiz_tags`
--

INSERT INTO `quiz_tags` (`id`, `quiz_id`, `tag_id`) VALUES
(1, 4, 16),
(2, 4, 13),
(3, 4, 11),
(4, 3, 17),
(5, 5, 17),
(12, 22, 17),
(15, 24, 21),
(13, 22, 18),
(18, 27, 17),
(19, 28, 22),
(22, 31, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `description`) VALUES
(1, 'Artefacto del detector'),
(2, 'Artefacto por contaminación'),
(3, 'Artefacto por extravasación de trazador'),
(4, 'Artefacto por interposición de metal'),
(5, 'Artefacto por movimiento del paciente'),
(6, 'Error de posicionamiento/alineamiento'),
(7, 'Fallo desconocido'),
(8, 'Hallazgo no concluyente por falta de imágenes'),
(9, 'Hallazgo no concluyente por posicionamiento erróneo'),
(10, 'Posible hallazgo fuera del FOV'),
(11, 'Fractura complicada'),
(12, 'Fractura infectada'),
(13, 'Retraso de consolidación de fractura'),
(14, 'Aflojamiento protésico mecánico'),
(15, 'Aflojamiento protésico séptico'),
(16, 'Síndrome de dolor regional complejo tipo I'),
(17, 'Imagen del dia'),
(18, 'Captación extraosea'),
(19, 'monoartritis'),
(21, 'Monoartritis'),
(22, 'Incidentaloma'),
(23, 'Síndrome de dolor regional complejo tipo II (neuropático)');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
