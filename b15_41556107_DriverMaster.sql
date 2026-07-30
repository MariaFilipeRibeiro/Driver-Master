-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql201.byetcluster.com
-- Generation Time: Jun 21, 2026 at 06:28 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `b15_41556107_DriverMaster`
--
CREATE DATABASE IF NOT EXISTS `b15_41556107_DriverMaster` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `b15_41556107_DriverMaster`;

-- --------------------------------------------------------

--
-- Table structure for table `carrinho`
--

DROP TABLE IF EXISTS `carrinho`;
CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `comprado` tinyint(1) DEFAULT 0,
  `data` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `carrinho`
--

INSERT INTO `carrinho` (`id`, `user_id`, `produto_id`, `quantidade`, `comprado`, `data`) VALUES
(2, 3, 10, 2, 1, '2026-06-17 15:24:29'),
(3, 3, 10, 2, 1, '2026-06-18 17:53:31'),
(4, 3, 6, 1, 1, '2026-06-18 17:53:31'),
(5, 3, 7, 1, 1, '2026-06-18 17:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Componentes'),
(2, 'Imagem e Som'),
(3, 'Software'),
(4, 'Acessórios'),
(5, 'Gaming');

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `stock`, `categoria_id`, `imagem`) VALUES
(1, 'Memória RAM UDIMM Kingston Fury Beast 64GB (2x32GB) DDR5-6400MHz', 'AIOR DESEMPENHO JÁ NA VELOCIDADE INICIAL\r\nCom uma velocidade inicial agressiva, a DDR5 é 50% mais rápida do que a DDR4.\r\n\r\nMAIOR ESTABILIDADE PARA OVERCLOCK\r\nOn-die ECC (ODECC) ajuda a manter a integridade dos dados para apoiar um desempenho de última geração enquanto ultrapassa os limites!\r\n\r\nMAIOR EFICIÊNCIA\r\nCom o impulso do dobro de bancos e do comprimento de \"burst\" e dois subcanais 32-bit independentes, a excepcional gestão dos dados da DDR5 brilha nos mais recentes jogos, programas e aplicações exigentes.\r\n\r\nCOMPATÍVEL COM INTEL® XMP 3.0\r\nTimings pré-otimizados avançados, velocidade e voltagem para desempenho de overclock, possibilidades de se salvarem novos perfis XMP de usuário personalizáveis utilizando um PMIC programável.\r\n\r\nDISSIPADOR DE CALOR DE PERFIL BAIXO\r\nUm dissipador de calor com novo design combina estilo e extraordinária funcionalidade de refrigeração.\r\n\r\nEspecificações:\r\nCapacidade: 64GB (2x32GB)\r\nTipo: DIMM DDR5\r\nVelocidade: 6400 MHz\r\nTensão: 1.4V\r\nLatência: 32-39-39\r\nDimensões do produto: 133.3 x 6.6 x 34.9 mm\r\nPerfis suportados:\r\nIntel® XMP 3.0\r\nAMD® EXPO', '1000.00', 25, 1, '6a314ede7f759.jpg'),
(2, 'Ventoinha 120mm Corsair iCUE (Triple Fan Kit)', 'O Kit básico de ventoinha PWM de 120mm CORSAIR iCUE LINK QX120 RGB contém três ventoinhas cada com quatro circuitos de iluminação distintos e um iCUE LINK System Hub incluído para uma montagem com um só cabo.\r\n\r\nUMA CRIAÇÃO MELHOR. E MAIS INTELIGENTE.\r\nTransforme a maneira de montar um PC DIY (faça você mesmo) com uma tecnologia inovadora de cabo único que deteta e configura automaticamente as suas ventoinhas de PC, coolers de CPU e outros componentes internos.\r\n\r\nINTELIGENTE E SOFISTICADO\r\nIlumine o seu sistema de qualquer ângulo e use modos de iluminação exclusivos como o pulsante Time Warp. Desfrute de recursos de componentes inteligentes como sensores de temperatura e comunicação de duas vias com o iCUE LINK System Hub.\r\n\r\nMANUTENÇÃO DA REFRIGERAÇÃO\r\nAs potentes ventoinhas controladas por PWM giram de 480 RPM a 2.400 RPM, movimentando até 63.1 CFM de ar com 3,8mm-H2O de pressão estática.\r\n\r\nMODO ZERO RPM\r\nO controlo PWM permite que configure com precisão a velocidade das ventoinhas, para que elas girem na rapidez que deseja, resultando em menos ruído geral, já que o modo Zero RPM permite que elas parem de funcionar completamente com cargas baixas.\r\n\r\n \r\n\r\nEspecificações:\r\nDiâmetro do ventilador: 120 mm\r\nVelocidade de rotação (min): 480 RPM\r\nVelocidade de rotação (máxima): 2400 RPM\r\nFluxo de ar máximo: 63.1 cfm\r\nPressão máxima do ar: 3.8 mmH2O\r\nConexão: 4 Pinos (PWM)\r\nNível de ruído da ventoinha (mín.): 10 dB\r\nNível de ruído do ventilador (máximo): 37 dB\r\n \r\n\r\nConteúdo da embalagem:\r\n3x ventoinhas CORSAIR QX120 RGB\r\n4x Conectores iCUE LINK\r\n1x System Hub iCUE LINK\r\n1x Cabo iCUE LINK, 600mm\r\n1x Cabo de alimentação PCIe, 150mm\r\n1x Cabo USB, 300 mm\r\n12x Parafusos de montagem da ventoinha', '121.97', 9, 1, '6a314f44c28be.jpg'),
(3, 'Fonte de Alimentação ATX MSI MEG Ai1600T PCIE5', 'CERTIFICAÇÃO DE EFICIÊNCIA TITANIUM \r\nA eficiência da sua fonte de alimentação tem um impacto significativo no consumo geral de energia. Uma certificação Titanium serve como uma excelente referência para eficiência energética, assegurando um menor consumo de energia e mantendo desempenho ótimo a níveis de eficiência mais elevados.\r\n\r\nCONCEPÇÃO TOTALMENTE DIGITAL \r\nEsta fonte de alimentação é fornecida com MCU para controlo de PFC, LLC e e a proteção de dados melhoram significativamente a precisão. Esta arquitetura permite gerir ajustes dinâmicos, proporcionando uma saída de energia mais estável e eficiente.\r\n\r\n \r\n\r\nCaracterísticas Principais:\r\nSuporta placas gráficas Nvidia® GeForce RTXTM Série 50\r\nConectores duplos nativos 12V-2x6\r\nMateriais de nível de servidor, MOSFET PFC intercalado SiC\r\nCertificação Titanium tri-certificada (80 PLUS / Cybenetics / PPLP)\r\nPreparado para PCIe 5.1 e ATX 3.1\r\nCabos 12V-2x6 de duas cores\r\nCondensador 100% japonês de 105oC\r\nDesign totalmente digital com MCU de controlo\r\nProteção de nível industrial com OCP/ OTP/ OPP/ SCP/ OVP/ UVP/ SIP/ NLO\r\n \r\n\r\nEspecificações:\r\nCertificação: 80 PLUS Titanium (até 94%)\r\nPotência: 1600W\r\nModular: Sim\r\nTamanho ventoinha: 135 mm\r\nDimensões: 190 x 150 x 86 mm\r\nTipo PFC: PFC intercalado\r\nEntrada: 100-240V~ / 50-60Hz\r\nProteções: OCP / OTP / OPP / SCP / OVP / UVP / SIP / NLO\r\nConectores:\r\n1x ATX (24 Pin)\r\n2x EPS (4+4 Pin)\r\n2x PCIe 5.1 (16 Pin)\r\n9x PCIe (6+2 Pin)\r\n8x SATA\r\n4x Peripheral (4 Pin)', '659.00', 5, 1, '6a314f75f237e.jpg'),
(4, 'Monitor Curvo Acer Predator Z57', 'Prepare-se para uma experiência de jogo mais imersiva e realista com o monitor gaming Acer Predator Z57 Z57bmiiphuzx. O painel Ultra Wide OLED de 57\" oferece cores fiéis em resolução 8K FUHD com amplos ângulos de visão (178/178 graus).\r\n\r\nFREQUÊNCIA DE ATUALIZAÇÃO DE 120 HZ\r\nExperimente uma visualização ultra-fluida com uma renderização de fotogramas mais rápida e um menor atraso de entrada com uma rápida frequência de atualização de 120 Hz.\r\n\r\nAMD FREESYNC PREMIUM\r\nPermite um jogo fluido e reduz o efeito de tearing no ecrã. As imagens do jogo são reproduzidas de forma fluida e suave.\r\n\r\n \r\n\r\nEspecificações:\r\nImagem:\r\nTamanho do ecrã: 57\"\r\nEcrã Curvo: Sim (1000R)\r\nRácio de aspecto: 32:9\r\nTipo de painel: VA (Mini-LED)\r\nResolução: 7680 x 2160 pixels\r\nBrilho máximo: 1000 cd/m²\r\nContraste: 1500000:1\r\nTempo de resposta: 1ms\r\nTaxa de atualização: 120 Hz\r\nÂngulos de visualização: 178/178º\r\nÁudio: 2x 10W\r\nPortas I/O:\r\n1 x DisplayPort\r\n2 x HDMI 2.1\r\n1 x USB-C\r\n4 x USB 3.2\r\n1 x Jack 3.5 mm\r\nDesign Mecânico:\r\nNorma VESA: 100 x 100mm\r\nAjuste em inclinação: Sim (-5º ~ 20º)\r\nAjuste giratório: Sim (+/-30°)\r\nAjuste em altura: Até 11cm\r\nConsumo Energético:\r\nEficiência: Classe G\r\nConsumo em standby: 0.5W\r\nDimensões: 1313 x 539 x 545 mm\r\nPeso: 18.3 Kg', '1996.00', 10, 2, '6a314faa8f425.jpg'),
(5, 'Televisão Smart TV TCL C8L Series 98C8L (2026) 98\"', 'A TCL 98C8L é uma TV LED 4K Mini LED desenvolvida para oferecer uma experiência visual e sonora de topo. O brilho máximo de 6000 nits, aliado às 4032 zonas de dimming, garante um contraste elevado, maior profundidade de imagem e excelente detalhe mesmo em cenas muito luminosas ou escuras. O painel Ultimate Panel com filtro antirreflexo e tecnologia Ultimate Color permite um espectro de cores alargado até 100% BT.2020, proporcionando imagens mais realistas e vibrantes. O processador TSR AiPQ, suportado pela IA TCL, analisa e otimiza automaticamente os conteúdos, ajustando cor, contraste e nitidez em tempo real. Com taxa de atualização nativa de 144 Hz, esta TV assegura maior fluidez de imagem, sendo ideal para conteúdos de ação e jogos. O sistema Áudio by BANG&OLUFSEN complementa a qualidade de imagem com um som claro, potente e envolvente, elevando a experiência de entretenimento em casa.\r\n\r\n \r\n\r\nPainel:\r\nDiagonal do ecrã: 98\"\r\nResolução: 4K Ultra HD\r\nTecnologia de ecrã: SQD-Mini LED\r\nRácio de aspeto nativo: 16:9\r\nTaxa de atualização nativa: 144 Hz (MEMC a 120Hz)\r\nBrilho: 6000 nits (máximo)\r\nTecnologias HDR: HDR 10+ | HDR 10 | HLG | Dolby Vision IQ | IMAX\r\nTecnologia de atualização: AMD FreeSync Premium Pro\r\nÁudio:\r\nSistema de áudio: Bang & Olufsen\r\nTecnologia de som: DTS:X | Dolby Atmos\r\nSmart TV:\r\nSmart TV: Sim\r\nProcessador: Quad-Core\r\nTelevisão com Internet: Sim\r\nSistema operativo instalado: Google TV\r\nRede:\r\nNormas Wi-Fi: Wi-Fi 6\r\nBluetooth: 5.4\r\nLAN Ethernet: Sim\r\nNavegador Web: Sim\r\nConectividade: \r\n4x HDMI\r\n1x USB 3.0\r\n1x Satélite\r\n1x Antena/Cabo\r\n1x Ótica\r\n1x CI Slot\r\n1x RJ45\r\nDimensões:\r\nCom suporte: 2166 x 1272~1302 x 420 mm\r\nSem suporte: 2166 x 1302 x 420 mm\r\nPeso:\r\nCom suporte: 58.5 Kg\r\nSem suporte: 57.5 Kg\r\nNorma VESA: 600 x 500 mm', '3999.00', 50, 2, '6a314fe11171e.png'),
(6, 'Mini Projetor Laser Hisense C2 ULTRA 4K UHD 65\"', 'Crie a experiência de visualização imersiva. Este projetor adapta-se a qualquer tamanho de ecrã, de 65\" a 300\", para que possa projetar numa parede da sua sala de estar ou ter uma experiência de cinema no seu quintal.\r\n\r\nCaracterísticas Principais:\r\nResolução 4K UHD\r\n3000 Lúmenes\r\nMais de 25000h de entretenimento\r\nZoom Óptico\r\nSubwoofer incorporado\r\nAlexa incorporado\r\n \r\n\r\nEspecificações:\r\nProjeção: 65\"-300\"\r\nResolução: 4K\r\nSuporte Gimbal:\r\n360º horizontal\r\n135º vertical\r\nFormato: 16:9\r\nTecnologias HDR:\r\nHDR10+\r\nHDR10\r\nHLG\r\nDolby Vision\r\nBrilho: 3000 lúmens\r\nTecnologia: DLP\r\nSuperfície do ecrã: Plano\r\nTipo TOF: 3D TOF\r\nDeslocação da lente: Não\r\nFoco: Sim (Auto Focus e Manual Focus)\r\nZoom (Óptico ou Digital): Sim\r\nTampa da lente: Não\r\nVida útil da fonte de luz: 25,000 horas (Luminosidade caí 50%)\r\nProteção Ocular: Sim\r\nResistência a água e poeiras (IP): Não\r\nConector de segurança Kensington: Não\r\nSistema Operativo: VIDAA U7.6\r\nÁudio:\r\nSaída: 2x 10W + 20W Powered by JBL\r\nDTS Decoding: DTS-X\r\nAC-4: Sim\r\nWisa SoundSend: Sim\r\nConectividade:\r\n2x HDMI 2.1\r\n2x USB 3.0\r\n1x Saída S/PDIF\r\n1x Saída para auscultadores\r\n1x RJ45\r\nWireless 6E & Bluetooth 5.3\r\nDimensões: 247 x 247 x 286 mm\r\nPeso: 6.3 Kg', '753.00', 2, 2, '6a315009948a4.png'),
(7, 'Microsoft Windows 11 Pro Key', 'Ativa o Windows 11 Professional no teu PC com um código digital instantâneo. A edição Pro oferece funcionalidades avançadas face à versão Home, incluindo encriptação BitLocker, virtualização Hyper-V, acesso remoto, Windows Sandbox e ferramentas de gestão empresarial como Política de Grupo e integração com Azure Active Directory.\r\nIdeal para profissionais, programadores e pequenas empresas que precisam de mais desempenho, segurança e controlo do seu sistema operativo.\r\nComo ativar:\r\n\r\nDescarrega o Windows 11 em microsoft.com\r\nInstala o sistema operativo\r\nInsere o código em Definições > Sistema > Ativação\r\n\r\nAtivação rápida, segura e sem necessidade de suporte físico.', '15.00', 449, 3, '6a315028ab468.jpg'),
(8, 'Kaspersky Premium (2 Years / 10 Devices) Key', 'Com o Kaspersky PREMIUM, protege toda a família com segurança completa e avançada. Inclui antivírus multicamada, VPN ilimitada, Gestor de Palavras-passe e armazenamento seguro de documentos, com monitorização em tempo real de fugas de dados.\r\nPrincipais funcionalidades:\r\n\r\nProteção de Identidade – Bloqueia acessos não autorizados e mantém os teus documentos seguros\r\nProteção da Rede Doméstica – Monitoriza o teu Wi-Fi e alerta para ligações suspeitas\r\nProteção Bancária – Protege pagamentos e deteta palavras-passe comprometidas\r\nProteção Contra Hackers – Firewall, anti-ransomware e proteção contra cryptojacking\r\nOtimização do Dispositivo – Melhora o desempenho e monitoriza o estado do disco rígido\r\nSuporte Remoto Premium – Assistência dedicada para resolver qualquer problema de segurança\r\n\r\nNavega, joga e trabalha com total privacidade e sem interrupções.', '25.00', 350, 3, '6a31505554b1b.png'),
(9, 'Pasta Térmica Arctic MX-4 45g', 'QUALIDADE COMPROVADA\r\nO design das embalagens de pasta térmica da Arctic mudou várias vezes ao longo dos anos. A fórmula da composição permaneceu inalterada; portanto, as suas pastas MX representam alto desempenho e qualidade há anos.\r\n\r\nFÁCIL DE APLICAR\r\nCom uma consistência ideal, a MX-4 é muito fácil de usar, mesmo para iniciantes.\r\n\r\nMELHOR QUE O METAL LÍQUIDO\r\nO composto ARCTIC MX-4 é composto por micropartículas de carbono que levam a uma condutividade térmica extremamente alta. Garante que o calor gerado pelo CPU ou GPU seja dissipado com rapidez e eficiência. Soberba em desempenho, a MX-4 é o melhor companheiro para overclockers e entusiastas.\r\n\r\nAPLICAÇÃO SEGURA\r\nNão contém partículas metálicas, portanto, a condutividade elétrica não será um problema. Ao contrário do composto de prata e cobre, garante que o contato com qualquer pino elétrico não resulte em danos de qualquer espécie.\r\n\r\nEspecificações:\r\nQuantidade: 45g\r\nCondutividade térmica: 8.5 W/(mK)\r\nViscosidade: 870 poise\r\nDensidade: 2.50 g/cm³\r\nResistividade volumétrica: 3.8 x 1013 ?-cm', '25.00', 15, 1, '6a3150856abca.jpg'),
(10, 'Adesivo Térmico Thermal Grizzly Putty Pro 30g', 'A TG Putty é uma alternativa não condutora de eletricidade às almofadas térmicas convencionais. Como um preenchimento de espaços fácil de aplicar e flexível, compensa as diferenças de altura, tornando-a ideal como substituto das almofadas térmicas nas GPUs.\r\n\r\nQUANTIDADE RECOMENDADA DE TG PUTTY\r\nPara modificar uma única placa gráfica menor sem placa traseira ativa (por exemplo, GeForce GTX 1060), é necessário aproximadamente um recipiente de 30 gramas de TG Putty. Para placas gráficas maiores, como a GeForce RTX 4090, um recipiente de 30 gramas geralmente é suficiente para a memória (VRAM) e os reguladores de tensão (VRM) na parte frontal. Na parte traseira, geralmente há um espaço uniforme e maior entre o PCB e a placa traseira em placas gráficas maiores. Nesse caso, são necessários aproximadamente dois recipientes de 30 gramas para preencher completamente esse espaço.\r\n\r\nEspecificações:\r\nQuantidade: 30 gramas\r\nTemperatura de operação: -40 °C a +120 °C', '55.00', 1, 1, '6a3150adf015f.png'),
(11, 'Soundbar TCL Q85HE Dolby Atmos', 'A TCL Q85HE é uma soundbar premium 7.1.4?canal que transforma a tua sala num verdadeiro cinema em casa com som envolvente e potente. Com até 860?W de potência total, incorpora Dolby Atmos e DTS:X para criar uma paisagem sonora tridimensional, com áudio que vem de cima, dos lados e atrás, ideal para filmes, séries, jogos e música. A tecnologia RayDanz expande o palco sonoro, enquanto a calibração automática AI Sonic adapta o som ao teu espaço para a melhor experiência possível. Inclui subwoofer sem fios e altifalantes surround traseiros sem fios para graves profundos e imersão total. Conectividade versátil com HDMI?2.1 (incl. eARC), Bluetooth 5.0, Wi?Fi, optical e USB facilita a ligação a TVs, consolas e dispositivos móveis. Modos de som dedicados para filmes, música, voz, jogos e mais completam um sistema de áudio robusto e sofisticado para entretenimento doméstico de alta qualidade.', '469.90', 15, 2, '6a3150d4a560a.jpg'),
(12, 'Motherboard ATX Gigabyte B650 Gaming X AX V2 SktAM5', 'As Motherboards GIGABYTE Ultra Durable™ construídas com componentes ideais de dentro para fora proporcionam o melhor desempenho e uma plataforma intemporal', '139.90', 9, 5, '6a315e7f12813.jpg'),
(13, 'Gamepad SCUF Omega Wireless Gaming Performance PC/PS5 - Steel Gray', 'Jogue para ganhar com o gamepad SCUF Omega Wireless para PS5 e PC.', '239.90', 4, 5, '6a315f3dcf61a.jpg'),
(14, 'Headset SteelSeries Arctis Nova 3 RGB Preto', 'Desafie as suas percepções de headsets de jogo com o melhor sistema acústico da classe Nova Acoustic, com potentes Drivers de Alta Fidelidade desenhados à medida. Um duo de software e hardware de sonho, para encher os ouvidos com notas altas cristalinas e graves profundos.', '81.90', 5, 4, '6a31604f6e3cb.jpg'),
(15, 'Rato Óptico Steelseries Rival 3 Wireless/Bluetooth Gen 2 18000DPI Branco', 'O SteelSeries Rival 3 Wireless Gen 2 é um rato gaming sem fios concebido para oferecer desempenho competitivo com total liberdade de movimentos. Equipado com tecnologia wireless de baixa latência, permite alternar entre ligação 2,4 GHz para gaming de alta performance e Bluetooth para maior versatilidade no dia a dia.', '37.90', 5, 4, '6a3160bdc4b72.jpg'),
(16, 'Portátil Asus TUF Gaming F16 (2025) 16\" FX608JMR-74B56CS1 Jaeger Gray', 'Como membro da família TUF Gaming, o portátil gaming Asus TUF Gaming F16 ostenta algumas especificações verdadeiramente impressionantes, dando-lhe acesso ao melhor dos jogos. Com um processador Intel® Core™ e uma placa gráfica para portátil NVIDIA® GeForce RTX™ 5060, o F16 pode facilmente executar os jogos mais recentes ou manter o seu fluxo de trabalho dentro do prazo.', '1299.99', 3, 5, '6a316131950ed.jpg'),
(17, 'Portátil Asus ROG Zephyrus Duo (2026) 16\" GX651AX-U94B59PB1 Stellar Grey', 'O ASUS ROG Zephyrus Duo (2026) GX651AX é um portátil gaming de topo que redefine produtividade e desempenho com um design inovador de duplo ecrã OLED de 16”. Equipado com o potente Intel Core Ultra 9 386H e gráfica NVIDIA GeForce RTX 5090, oferece performance extrema para jogos AAA e criação de conteúdo avançada. O seu sistema de dois ecrãs táteis 3K a 120Hz proporciona multitarefa fluida e novas formas de interação,', '6999.99', 1, 5, '6a3161e2d654f.jpg'),
(18, 'Cadeira Gaming Alpha Gamer Gaia Dark Grey Fabric V2', 'A ergonomia é um dos nossos principais focos de atenção; E como tal, desenvolvemos apoios de braço facilmente ajustáveis em altura, para um ótimo encaixe com a estrutura corporal de qualquer pessoa. Estas cadeiras gaming incluem duas almofadas: para apoio lombar e também para apoio da cabeça. Podes ajustar a inclinação do encosto para um posicionamento até 180 graus.', '159.90', 12, 4, '6a31630c23c78.jpg'),
(19, 'Cadeira Gaming Alpha Gamer Zeta Branca/Preta', 'As cadeiras gaming Alpha Gamer Zeta incluem duas almofadas: para apoio lombar e também para apoio da cabeça. Caso pretendas sentar-te numa posição mais reta, remove as almofadas. Podes ajustar a inclinação do encosto para um posicionamento até 180 graus. Alto ou baixo, pensámos em ti! As cadeiras gaming Alpha Gamer Zeta possuem 9 cm de capacidade de ajuste da altura do assento.', '169.90', 13, 4, '6a3163d45efe7.jpg'),
(20, 'Cadeira Gaming Alpha Gamer Alegra Evo Fabric Sage Green', 'O encosto reclinável ajusta-se dos 90° aos 135°, permitindo passar rapidamente de uma posição direita para uma mais relaxada. Já o assento inclinável varia dos 0° aos 14°, oferecendo um ajuste simples e personalizado para maior conforto.Almofada em espuma de memória, criada para se adaptar à tua forma e aliviar a pressão. O sistema de fixação magnético mantém a almofada cervical no lugar. Equipados com um mecanismo interno totalmente em metal para máxima durabilidade e movimento suave. Ajusta-os para a esquerda, direita, para frente, para trás, para cima, para baixo, ou em ângulos, para encontrar a posição perfeita e reduzir a tensão.', '349.00', 11, 4, '6a31645c9d729.webp'),
(21, 'Motherboard ATX Asus Prime Z890-P WiFi Skt1851', 'Design inspirador com elementos de exploração espacial e espetáculo Sci-Fi. Cabeçalhos RGB e ARGB para personalização de iluminação com suporte para ASUS Aura Sync. Pre-installed I/O shield e Q-Antenna para facilitar a montagem e melhorar a estética do sistema.', '217.90', 20, 1, '6a3164c527fcb.jpg'),
(22, 'Processador AMD Ryzen 3 4100 3.8GHz Box', 'Construído para executar. Concebido para vencer.\r\nMais velocidade. Mais memória. Maior largura de banda.\r\nEmpurre ao máximo todos os limites, e esprema todo o seu desempenho até à última gota - Leve-os ao limite.\r\nOs processadores AMD Ryzen da Série 4000 foram desenhados para superar as expectativas e estabelecer um novo padrão para processadores de alta performance.', '58.00', 15, 1, '6a3166a8d34de.jpg'),
(23, 'Monitor Asus ROG Strix XG27AQWMG WOLED 27\" QHD 280Hz 16:9 FreeSync Premium', 'O ROG Strix OLED XG27AQWMG é um monitor para jogos WOLED QHD TrueBlack Glossy™ de 27 polegadas que oferece imagens nítidas e possui tecnologia OLED Tandem que oferece 15% mais brilho máximo, 25% mais volume de cor e uma vida útil 60% mais longa em comparação com os monitores WOLED da geração anterior. Com o novo sensor de proximidade Neo Proximity Sensor integrado no pacote ASUS OLED Care Pro, o monitor é capaz de detetar quando o utilizador se afasta e passar automaticamente para um ecrã preto para evitar o desgaste do painel. Graças a um suporte compacto, o XG27AQWMG ocupa um espaço mínimo na secretária. Além disso, oferece aos utilizadores uma ampla gama de opções de conectividade, incluindo DisplayPort™ 1.4 (DSC), HDMI® 2.1 e hub USB.', '549.00', 15, 2, '6a3167841925b.jpg'),
(24, 'Smartphone Xiaomi 17 Ultra 6.85\" 16GB/512GB Dual SIM Black', 'O Xiaomi 17 Ultra é o novo topo de gama da Xiaomi, concebido para quem exige performance e fotografia de nível profissional. Equipado com o potente processador Qualcomm Snapdragon 8?Elite?Gen?5 e 16?GB de RAM com 512 GB de armazenamento ultra?rápido, garante fluidez em multitarefa, jogos e aplicações criativas. O grande destaque está no sistema de câmaras Leica: sensor principal 1? de 50?MP com OIS, sensor ultra?wide de 50?MP e telefoto periscópico de 200?MP com zoom óptico contínuo – ideal para fotos e vídeos detalhados até 8K. O ecrã LTPO AMOLED de 6.85? com 120?Hz e brilho até 3500 nits oferece visualização envolvente e cores vibrantes, enquanto a bateria de 6000?mAh com carregamento rápido de 90?W (e 50?W sem fios) assegura autonomia para todo o dia. Com design premium, certificações de resistência e o moderno HyperOS 3 baseado em Android 16, o Xiaomi 17 Ultra combina potência, fotografia e autonomia.', '1229.90', 14, 4, '6a32c06daafc7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','admin') DEFAULT 'cliente'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'Administrador', 'admin@loja.com', '$2y$10$gfCffhAVhJepJMEfYETw4.MO79DsvytmHzyz3bAGZNuuWr/NYeiIy', 'admin'),
(2, 'Diogo Dias', 'diogo@gmail.com', '$2y$10$Dev.R5GRGQDYwNXCb2NzRehOzrnu/n5byHU5F3VE5exBc15VfrD/e', 'cliente'),
(3, 'Maria Ribeiro', 'maria@gmail.com', '$2y$10$rOSWf/kGFnek3Gunga/x0OkF1vN65LycSu9P7Vc3p4.PMN40VnpX2', 'cliente'),
(4, 'Ricardo Farinha', 'RicardoFarinha@gmail.com', '$2y$10$.Re3K1Z0f2FX9WciGvp2Be4RRHKGT2lyEk35ZkDBsLQzfDGozMPy.', 'cliente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
