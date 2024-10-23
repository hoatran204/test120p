CREATE DATABASE IF NOT EXISTS db_Tran_Thi_Ngoc_Hoa;
USE db_Tran_Thi_Ngoc_Hoa;

CREATE TABLE Course (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Tittle VARCHAR(255),
    Description TEXT,
    ImageUrl VARCHAR(255)
);
