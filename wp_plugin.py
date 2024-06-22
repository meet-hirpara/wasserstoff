from flask import Flask, request, jsonify
from rag_model import process_query_with_chain_of_thought, custom_retriever

app = Flask(__name__)

@app.route('/chat', methods=['POST'])
def chat():
    data = request.json
    user_query = data.get('query')
    previous_context = data.get('context', [])
    response = process_query_with_chain_of_thought(user_query, previous_context, custom_retriever)
    return jsonify({"response": response})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
