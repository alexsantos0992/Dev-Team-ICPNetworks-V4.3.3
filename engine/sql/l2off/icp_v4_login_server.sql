/*
 Navicat Premium Data Transfer

 Source Server         : L2OFF
 Source Server Type    : SQL Server
 Source Server Version : 11006020
 Source Host           : 177.23.234.106:1433
 Source Catalog        : lin2db
 Source Schema         : dbo

 Target Server Type    : SQL Server
 Target Server Version : 11006020
 File Encoding         : 65001

 Date: 22/06/2021 15:59:08
*/


-- ----------------------------
-- Table structure for icp_accounts
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[icp_accounts]') AND type IN ('U'))
	DROP TABLE [dbo].[icp_accounts]
GO

CREATE TABLE [dbo].[icp_accounts] (
  [id] int  IDENTITY(1,1) NOT NULL,
  [login] varchar(45) COLLATE Korean_Wansung_CI_AS  NULL,
  [email] varchar(255) COLLATE Latin1_General_CI_AS  NOT NULL,
  [acc_id] int DEFAULT 0 NOT NULL,
  [status] int DEFAULT 0 NOT NULL,
  [repass] int DEFAULT 1 NOT NULL,
  [accessLevel] int DEFAULT '0' NOT NULL,
  [vip_end] smalldatetime  NOT NULL
)
GO

ALTER TABLE [dbo].[icp_accounts] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of icp_accounts
-- ----------------------------
SET IDENTITY_INSERT [dbo].[icp_accounts] ON
GO

SET IDENTITY_INSERT [dbo].[icp_accounts] OFF
GO


-- ----------------------------
-- Table structure for icp_accounts_ip
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[icp_accounts_ip]') AND type IN ('U'))
	DROP TABLE [dbo].[icp_accounts_ip]
GO

CREATE TABLE [dbo].[icp_accounts_ip] (
  [id] int  IDENTITY(1,1) NOT NULL,
  [ip] varchar(255) COLLATE Latin1_General_CI_AS  NOT NULL,
  [date] smalldatetime  NOT NULL,
  [login] varchar(255) COLLATE Korean_90_CI_AI  NOT NULL
)
GO

ALTER TABLE [dbo].[icp_accounts_ip] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of icp_accounts_ip
-- ----------------------------
SET IDENTITY_INSERT [dbo].[icp_accounts_ip] ON
GO

SET IDENTITY_INSERT [dbo].[icp_accounts_ip] OFF
GO


-- ----------------------------
-- Auto increment value for icp_accounts
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[icp_accounts]', RESEED, 1)
GO


-- ----------------------------
-- Primary Key structure for table icp_accounts
-- ----------------------------
ALTER TABLE [dbo].[icp_accounts] ADD CONSTRAINT [PK__icp_acco__3213E83F8C14AB1B_copy1] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for icp_accounts_ip
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[icp_accounts_ip]', RESEED, 1)
GO


-- ----------------------------
-- Primary Key structure for table icp_accounts_ip
-- ----------------------------
ALTER TABLE [dbo].[icp_accounts_ip] ADD CONSTRAINT [PK__icp_acco__3213E83F00B8DF1F_copy1] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO

