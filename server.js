const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();

app.use(cors());
app.use(bodyParser.json());

// DB connection
const db = mysql.createConnection({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'tradeverse',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'user_system',
  port: process.env.DB_PORT || 3306
});

db.connect(err => {
  if (err) throw err;
  console.log('Connected to MySQL');
});

// Route to insert user data
app.post('/api/users', (req, res) => {
  const { firstName, lastName, phoneNumber, idNumber } = req.body;
  const sql = 'INSERT INTO user (first_name, last_name, phone_number, id_number) VALUES (?, ?, ?, ?)';
  db.query(sql, [firstName, lastName, phoneNumber, idNumber], (err, result) => {
    if (err) {
      console.error(err);
      res.status(500).json({ message: 'Error inserting user' });
    } else {
      res.status(200).json({ message: 'User inserted successfully' });
    }
  });
});

app.listen(3000, () => {
  console.log('Server running on http://localhost:3000');
});
