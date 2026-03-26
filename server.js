const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const crypto = require('crypto');
const path = require('path');

const app = express();
const dbPath = path.join(__dirname, 'users.db');
const db = new sqlite3.Database(dbPath, (err) => {
    if (err) {
        console.error('Failed to open database:', err);
        process.exit(1);
    }
    console.log('Database connection established');
});

db.on('error', (err) => {
    console.error('Database error:', err);
});

const cors = require('cors');

// Serve static files from the current directory
app.use(express.static(path.join(__dirname)));

app.use(express.json());
app.use(cors({
  origin: '*',
  methods: ['GET','POST','PUT','OPTIONS'],
  allowedHeaders: ['Content-Type']
}));

function simpleHash(str) {
    return crypto.createHash('sha256').update(str).digest('hex');
}

function initDatabase(callback) {
    // Drop existing table to ensure clean structure
    db.serialize(() => {
        db.run(`DROP TABLE IF EXISTS users`, (err) => {
            if (err) {
                console.error('Error dropping table:', err);
                return callback(err);
            }
            
            db.run(`CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                student_number TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                email TEXT,
                phone TEXT,
                fullname TEXT,
                course TEXT,
                bio TEXT,
                avatar TEXT,
                role TEXT DEFAULT 'student'
            )`, (err) => {
                if (err) {
                    console.error('Error creating table:', err);
                    return callback(err);
                }
                console.log('Database initialized successfully');
                callback(null);
            });
        });
    });
}

// Initialize database and start server
initDatabase((err) => {
    if (err) {
        console.error('Failed to initialize database:', err);
        process.exit(1);
    }

    app.listen(3000, () => {
        console.log("Server running on http://localhost:3000");
    });
});

app.post('/login', (req, res) => {
    const { student_number, password } = req.body;
    const hashed = simpleHash(password);

    db.get(`
        SELECT id, student_number, role, email, phone, fullname, course, bio, avatar
        FROM users
        WHERE student_number = ? AND password = ?
    `, [student_number, hashed], (err, row) => {
        if (err) return res.status(500).json({ error: err.message });
        if (row) {
            res.json({ success: true, user: row });
        } else {
            res.json({ success: false, message: "Wrong student number or password" });
        }
    });
});

app.post('/register', (req, res) => {
    const { student_number, password } = req.body;
    const hashed = simpleHash(password);

    db.run("INSERT INTO users (student_number, password, role) VALUES (?, ?, ?)", [student_number, hashed, 'student'], function(err) {
        if (err) return res.json({ success: false, message: "Student number already exists" });
        
        res.json({ success: true });
    });
});

app.get('/profile/:student_number', (req, res) => {
    const { student_number } = req.params;
    
    db.get(`
        SELECT id, student_number, role, email, phone, fullname, course, bio, avatar
        FROM users
        WHERE student_number = ?
    `, [student_number], (err, row) => {
        if (err) return res.status(500).json({ error: err.message });
        if (row) {
            res.json({ success: true, profile: row });
        } else {
            res.json({ success: false, message: "User not found" });
        }
    });
});

app.put('/profile/:student_number', (req, res) => {
    const { student_number } = req.params;
    const { fullname, bio, course, email, phone, avatar } = req.body;

    db.run(`
        UPDATE users 
        SET fullname = ?, course = ?, bio = ?, email = ?, phone = ?, avatar = ?
        WHERE student_number = ?
    `, [fullname, course, bio, email, phone, avatar, student_number], (err) => {
        if (err) return res.status(500).json({ error: err.message });
        if (this.changes === 0) return res.status(404).json({ success: false, message: "User not found" });
        res.json({ success: true, message: "Profile updated" });
    });
});

app.get('/students', (req, res) => {
    db.all(`
        SELECT id, student_number, role, email, phone, fullname, course, bio, avatar
        FROM users
        ORDER BY student_number
    `, (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ success: true, students: rows });
    });
});

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('\nClosing database connection...');
    db.close((err) => {
        if (err) {
            console.error('Error closing database:', err);
        } else {
            console.log('Database connection closed');
        }
        process.exit(0);
    });
});

