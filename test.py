import requests
from flask import Flask, request, jsonify
from telegram.ext import Application, MessageHandler, filters

app = Flask(__name__)

# Replace with your bot token
BOT_TOKEN = "7802436801:AAFn2XiuESgJYBL_Ecl21GI-vm0rblR6UDM"

@app.route('/message', methods=['POST'])
def message():
    data = request.json
    print(f"Received message: {data}")
    return jsonify({"status": "success"}), 200

async def handle_message(update, context):
    # Print all messages from the group
    chat_id = update.effective_chat.id
    user = update.effective_user.username
    text = update.message.text
    print(f"Message from {user} in chat {chat_id}: {text}")

    # Send message to Flask server
    url = "http://127.0.0.1:5000/message"
    payload = {
        "chat_id": chat_id,
        "user": user,
        "text": text
    }
    response = requests.post(url, json=payload)
    print(f"Response from server: {response.json()}")

def main():
    application = Application.builder().token(BOT_TOKEN).build()

    # Add a message handler
    application.add_handler(MessageHandler(filters.TEXT & (~filters.COMMAND), handle_message))

    # Start the bot
    application.run_polling()

if __name__ == "__main__":
    from threading import Thread

    # Run Flask server in a separate thread
    flask_thread = Thread(target=lambda: app.run(debug=True, use_reloader=False))
    flask_thread.start()

    # Run Telegram bot
    main()