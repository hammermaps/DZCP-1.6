# 📊 DZCP 1.6.2 Database Schema & Optimization

## Current Tables Overview

### Core Tables

#### users
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nick VARCHAR(255) UNIQUE NOT NULL COMMENT 'Username',
    user VARCHAR(255) UNIQUE COMMENT 'Alternative username (legacy)',
    pass VARCHAR(255) NOT NULL COMMENT 'Password hash (bcrypt)',
    email VARCHAR(255) UNIQUE NOT NULL COMMENT 'Email address',
    verified BOOLEAN DEFAULT FALSE COMMENT 'Email verification status',
    verified_token VARCHAR(255) COMMENT 'Email verification token',
    level INT DEFAULT 0 COMMENT 'User level/rank',
    squad INT DEFAULT 0 COMMENT 'Squad ID',
    time INT COMMENT 'Registration timestamp',
    lastvisit INT COMMENT 'Last visit timestamp',
    banned BOOLEAN DEFAULT FALSE,
    banreason TEXT,
    whereyouare VARCHAR(255),
    avatar VARCHAR(255) COMMENT 'Avatar filename',
    homepage VARCHAR(255),
    icq VARCHAR(20),
    aim VARCHAR(100),
    yim VARCHAR(100),
    msnm VARCHAR(100),
    signature TEXT,
    pic_id INT COMMENT 'Picture ID',
    
    INDEX idx_nick (nick),
    INDEX idx_email (email),
    INDEX idx_level (level),
    INDEX idx_squad (squad),
    INDEX idx_banned (banned),
    INDEX idx_lastvisit (lastvisit),
    CONSTRAINT fk_squad FOREIGN KEY (squad) REFERENCES squads(id) ON DELETE SET NULL
);
```

#### forums
```sql
CREATE TABLE forums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    pos INT DEFAULT 0 COMMENT 'Position/Order',
    type INT DEFAULT 0,
    last_post_time INT,
    last_post_user INT,
    post_count INT DEFAULT 0,
    topic_count INT DEFAULT 0,
    
    INDEX idx_name (name),
    INDEX idx_pos (pos),
    INDEX idx_last_post (last_post_time),
    CONSTRAINT fk_last_user FOREIGN KEY (last_post_user) REFERENCES users(id) ON DELETE SET NULL
);
```

#### forum_posts
```sql
CREATE TABLE forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    topic_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at INT NOT NULL,
    updated_at INT,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    edited BOOLEAN DEFAULT FALSE,
    
    INDEX idx_forum (forum_id),
    INDEX idx_topic (topic_id),
    INDEX idx_user (user_id),
    INDEX idx_created (created_at),
    CONSTRAINT fk_forum FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
    CONSTRAINT fk_topic FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### news
```sql
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    created_at INT NOT NULL,
    updated_at INT,
    published BOOLEAN DEFAULT FALSE,
    views INT DEFAULT 0,
    
    INDEX idx_title (title),
    INDEX idx_author (author_id),
    INDEX idx_created (created_at),
    INDEX idx_published (published),
    CONSTRAINT fk_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### clanwars
```sql
CREATE TABLE clanwars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date INT NOT NULL COMMENT 'Clanwar date/timestamp',
    opponent VARCHAR(255),
    our_score INT DEFAULT 0,
    their_score INT DEFAULT 0,
    server VARCHAR(255),
    map VARCHAR(255),
    event_id INT,
    
    INDEX idx_date (date),
    INDEX idx_opponent (opponent),
    INDEX idx_event (event_id)
);
```

## Optimization Strategies

### 1. Query Optimization

**N+1 Query Problem (BAD):**
```php
// Loads 1 query for users + N queries for each forum
foreach ($forums as $forum) {
    $lastPost = db("SELECT * FROM forum_posts WHERE forum_id = {$forum['id']} ORDER BY created_at DESC LIMIT 1");
}
```

**Optimized (GOOD):**
```php
// Single query with JOIN
$forums = $db->query(
    "SELECT f.*, fp.created_at as last_post FROM forums f
     LEFT JOIN forum_posts fp ON f.id = fp.forum_id
     WHERE fp.created_at = (SELECT MAX(created_at) FROM forum_posts WHERE forum_id = f.id)"
);
```

### 2. Indexing Strategy

**Primary Keys:**
```sql
PRIMARY KEY (id)  -- Always indexed
```

**Foreign Keys:**
```sql
INDEX idx_user_id (user_id),
FOREIGN KEY (user_id) REFERENCES users(id)
```

**Search Columns:**
```sql
INDEX idx_nick (nick),
INDEX idx_email (email),
INDEX idx_username (user)
```

**Filter Columns:**
```sql
INDEX idx_level (level),
INDEX idx_squad (squad),
INDEX idx_banned (banned),
INDEX idx_published (published)
```

**Sort/Date Columns:**
```sql
INDEX idx_created (created_at),
INDEX idx_lastvisit (lastvisit),
INDEX idx_date (date)
```

**Complex Queries (Composite Index):**
```sql
-- For: WHERE forum_id = X AND published = 1 ORDER BY created_at
INDEX idx_forum_published_created (forum_id, published, created_at)
```

### 3. Caching Strategy

**High-Hit Cache Keys:**
```php
'user_{id}'              // Single user data (TTL: 1 hour)
'forum_{id}'             // Forum info (TTL: 2 hours)
'news_{id}'              // News article (TTL: 24 hours)
'user_online'            // Online users list (TTL: 5 minutes)
'settings'               // App settings (TTL: 24 hours)
```

**Cache Tags for Invalidation:**
```php
$cache->set(
    "user_{$userId}",
    $userData,
    3600,
    ['users', "user_{$userId}"] // Tags for invalidation
);

// When user updates profile:
$cache->deleteByTag('users');  // Invalidates ALL user caches
```

### 4. Connection Pooling

**Nette\Database handles connection pooling:**
```php
// Single connection instance, reused across requests
$db = getNetteDb();
// Connection automatically managed by PDO
```

### 5. Query Result Caching

```php
class ForumRepository extends BaseRepository {
    public function getTopForums(int $limit = 10): array {
        return $this->cache->remember(
            'forums_top_' . $limit,
            fn() => $this->db->table('forums')
                ->orderBy('post_count DESC')
                ->limit($limit)
                ->fetchAll(),
            86400,  // 24 hour TTL
            ['forums']  // Tag for invalidation
        );
    }
}
```

### 6. Pagination for Large Result Sets

```php
public function getPosts(int $forumId, int $page = 1, int $perPage = 20): array {
    $offset = ($page - 1) * $perPage;
    
    $posts = $this->db->table('forum_posts')
        ->where('forum_id = ?', $forumId)
        ->orderBy('created_at DESC')
        ->limit($perPage)
        ->offset($offset)
        ->fetchAll();
    
    $total = $this->db->table('forum_posts')
        ->where('forum_id = ?', $forumId)
        ->count();
    
    return [
        'posts' => $posts,
        'total' => $total,
        'pages' => ceil($total / $perPage),
        'current_page' => $page
    ];
}
```

## Performance Metrics

### Query Performance Targets

| Query Type | Target | Status |
|------------|--------|--------|
| User lookup by ID | <1ms | ✅ Cached |
| Forum list | <5ms | ✅ Cached |
| Recent posts | <10ms | ✅ Query optimized |
| User search | <50ms | ✅ Full text index |
| Report generation | <1000ms | ⏳ Async job |

### Database Size Targets

| Component | Size | Optimization |
|-----------|------|---------------|
| users table | <100MB | Archiving old users |
| forum_posts | <1GB | Partitioning by date |
| news table | <50MB | Compression |
| Total | <2GB | Regular cleanup |

## Backup & Disaster Recovery

```bash
#!/bin/bash
# daily_backup.sh

DB_NAME="dzcp"
DB_USER="dzcp_user"
BACKUP_DIR="/backups/dzcp"
DATE=$(date +%Y%m%d_%H%M%S)

# Full backup
mysqldump -u $DB_USER -p $DB_NAME > "$BACKUP_DIR/dzcp_$DATE.sql"

# Compress
gzip "$BACKUP_DIR/dzcp_$DATE.sql"

# Keep last 30 days
find $BACKUP_DIR -name "dzcp_*.sql.gz" -mtime +30 -delete
```

## Schema Evolution

**Adding columns (zero-downtime):**
```sql
ALTER TABLE users ADD COLUMN new_field VARCHAR(255) DEFAULT NULL;
```

**Renaming columns:**
```sql
ALTER TABLE users CHANGE COLUMN old_name new_name VARCHAR(255);
```

**Creating indexes without locking:**
```sql
ALTER TABLE users ADD INDEX idx_new (column_name), ALGORITHM=INPLACE, LOCK=NONE;
```
