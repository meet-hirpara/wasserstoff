import requests
from sentence_transformers import SentenceTransformer
import faiss
import numpy as np
import pickle

# Initialize Sentence-BERT model
model = SentenceTransformer('all-MiniLM-L6-v2')

# Initialize Faiss index
index = faiss.IndexFlatL2(384)  # 384 is the dimension of the embeddings

def extract_text(post):
    return post['content']['rendered']

def generate_embeddings(text):
    embeddings = model.encode([text])
    return embeddings

def update_vector_database(post_id, embeddings):
    vector = np.array(embeddings).reshape(1, -1)
    index.add(vector)
    print(f"Post {post_id} embeddings updated.")

def fetch_latest_posts():
    response = requests.get('https://example.com/wp-json/wp/v2/posts')
    response.raise_for_status()  # Check if the request was successful
    return response.json()

def update_embeddings_on_new_post(post):
    text = extract_text(post)
    embeddings = generate_embeddings(text)
    update_vector_database(post['id'], embeddings)

# Fetch latest posts and update embeddings
try:
    posts = fetch_latest_posts()
    for post in posts:
        update_embeddings_on_new_post(post)
except requests.exceptions.RequestException as e:
    print(f"Error fetching posts: {e}")

# Save the index to disk for use with the retriever
faiss.write_index(index, 'faiss_index.idx')

# Save the posts for later use
with open('posts.pkl', 'wb') as f:
    pickle.dump(posts, f)
