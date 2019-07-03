-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 03 juil. 2019 à 07:09
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `catalogue`
--

--
-- Déchargement des données de la table `app_user`
--

INSERT INTO `app_user` (`id`, `email`, `roles`, `password`, `created_at`) VALUES
(1, 'tib@tib.fr', '{\"0\": \"ROLE_SUPER_ADMIN\", \"1\": \"ROLE_ADMIN\", \"2\": \"ROLE_MODERATEUR\"}', '$argon2i$v=19$m=1024,t=2,p=2$Uy9vdmZwSE1ONkNBVXlLcQ$OYBgJOGi3UqD7g01SrEpvOIQt3jzP4dZs1QoKsvl89o', '2019-07-01 00:00:00'),
(2, 'jules@jules.fr', '{\"0\": \"ROLE_ADMIN\", \"2\": \"ROLE_MODERATEUR\", \"3\": \"ROLE_SUPER_ADMIN\"}', '$argon2i$v=19$m=1024,t=2,p=2$ZnNUbHIuRDZiVkRlbFRlNg$LQBIHGo9rIIwWOgRII3LHhcgnlj2mfL050w5/VoXs3c', '2019-07-01 00:00:00'),
(3, 'agathe@agathe.fr', '[\"ROLE_MODERATEUR\"]', '$argon2i$v=19$m=1024,t=2,p=2$Um9rWlFPNUxyQ0VLTW0zaw$slfC4nTpTpM6KMcRksd9/sjJUsaVtmIv8dzW/y1RXbE', '2019-07-01 00:00:00');

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Plage'),
(2, 'Exterieur'),
(3, 'Meubles');

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190625130739', '2019-07-01 07:21:06'),
('20190626113939', '2019-07-01 07:21:06'),
('20190626140643', '2019-07-01 07:21:07'),
('20190627082856', '2019-07-01 07:21:07'),
('20190628090119', '2019-07-01 07:21:07'),
('20190701082411', '2019-07-01 08:24:51'),
('20190701124447', '2019-07-01 12:45:01'),
('20190702135914', '2019-07-02 14:00:47');

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `name`, `description`, `price`, `creation_date`, `modified_date`, `is_published`, `image_name`, `categories_id`, `slug`, `nb_views`) VALUES
(1, 'Ballon de volley', 'Pour jouer au volley sur la plage', '10.00', '2019-07-01 07:58:35', NULL, 1, 'ballon-de-volley1561967915.jpeg', 1, 'ballon-de-volley', 2),
(3, 'Parasol', 'Pour se protéger du soleil', '20.00', '2019-07-01 08:11:22', NULL, 1, 'parasol1561968682.jpeg', 2, 'parasol', 1),
(4, 'Salon de jardin', 'Ce grand salon de jardin 10 places aluminium et céramique comprend une table de jardin (204.5 cm x 100 cm x 74.5 cm), 8 chaises et 2 fauteuils de jardin. Le plateau de la table de jardin se compose de 10 lattes en céramique imitant à la perfection la couleur et l’aspect du bois. Cette matière à l’avantage d’être pratique à entretenir : elle ne tache pas et se nettoie facilement. Résistante à la chaleur et au froid, la céramique confère au salon de jardin un très haut niveau de solidité. La structure de la table de jardin est en aluminium gris foncé, ce qui la rend légère et facile à transporter. Les chaises et les fauteuils sont aussi en aluminium, avec une assise en textilène imperméable. Ils s’encastrent sous la table de jardin 10 places, et sont empilables pour optimiser l’espace de votre terrasse. Pour monter le salon de jardin, il suffit de fixer les pieds et de poser les lattes sur le plateau à l’aide de la notice et de la visserie fournies', '299.00', '2019-07-01 08:20:49', NULL, 1, 'salon-de-jardin1561969249.jpeg', 2, 'salon-de-jardin', 3),
(5, 'Seau de plage', 'Pour construire un château de sable', '10.00', '2019-07-01 08:25:51', NULL, 1, 'seau-de-plage1561969551.jpeg', 1, 'seau-de-plage', 1);

--
-- Déchargement des données de la table `produit_tag`
--

INSERT INTO `produit_tag` (`produit_id`, `tag_id`) VALUES
(1, 1),
(3, 1),
(4, 1),
(5, 1);

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `label`) VALUES
(1, 'Nouveautes'),
(2, 'Meilleures ventes');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
