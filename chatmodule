import openai
import yfinance as yf
from datetime import datetime, timedelta
import json

openai.api_key = "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"
def get_stock_price():
    print ("Call");

def get_historical_data(symbol):
    # Get today's date
    end_date = datetime.now().date()
    print (symbol);
    # Calculate start date (1 year ago)
    start_date = end_date - timedelta(days=365)

    try:
        # Retrieve historical data using yfinance
        data = yf.download(symbol, start=start_date, end=end_date)
        data_json = data.reset_index().to_json(orient='records', date_format='iso')
        data_json_string = json.dumps(data_json)

        return data_json_string
    except Exception as e:
        print(f"An error occurred: {e}")
        return None

def chat_with_bot():
    print("Welcome to the ChatBot!")
    user_name = input("Enter your name: ")

    while True:
        user_input = input(f"{user_name}: ")

        if user_input.lower() == 'exit':
            break

        bot_response = get_bot_response(user_input)
        print(f"ChatGPT: {bot_response}")

def get_bot_response(user_input):
    try:
        # Call the OpenAI API to generate a response
        my_assistant = openai.beta.assistants.create(
            instructions="You are a personal trading advisor, write and run Python code to answer the question.",
            name="Math Tutor",
            model="gpt-4-turbo",
            tools = [
            {
              "type": "function",
              "function": {
                "name": "get_historical_data",
                "description": "Get the historical data of a given stock symbol",
                "parameters": {
                  "type": "object",
                  "properties": {
                    "symbol": {
                      "type": "string",
                      "description": "The symbol of the stock"
                    },
                    "unit": {
                      "type": "string",
                      "enum": ["dollar", "USD"]
                    }
                  },
                  "required": ["symbol"]
                }
              }
            }
          ]
           # tool_choice = "auto"
        )

        thread = openai.beta.threads.create()
        message = openai.beta.threads.messages.create(
          thread_id=thread.id,
          role="user",
          content=user_input,
        )
        run = openai.beta.threads.runs.create_and_poll(
          thread_id=thread.id,
          assistant_id=my_assistant.id,
        )

        if run.status == 'completed':
          messages = openai.beta.threads.messages.list(
            thread_id=thread.id
          )
          print(messages)
        else:
          print(run.status)

        # Define the list to store tool outputs
        tool_outputs = []

        # Loop through each tool in the required action section
        for tool in run.required_action.submit_tool_outputs.tool_calls:
        #  print(tool.function.arguments.Symbol)
          if tool.function.name == "get_historical_data":
            data = json.loads(tool.function.arguments)
            # Extract the value associated with the key "Symbol"
            symbol = data.get("symbol")
            #print (tool.function.arguments)
            tool_outputs.append({
              "tool_call_id": tool.id,
              "output": get_historical_data(symbol)
            })
          elif tool.function.name == "get_rain_probability":
            tool_outputs.append({
              "tool_call_id": tool.id,
              "output": "0.06"
            })
          print (tool.function.name)

        # Submit all tool outputs at once after collecting them in a list
        if tool_outputs:
          try:
            run = openai.beta.threads.runs.submit_tool_outputs_and_poll(
              thread_id=thread.id,
              run_id=run.id,
              tool_outputs=tool_outputs
            )
            print("Tool outputs submitted successfully.")
          except Exception as e:
            print("Failed to submit tool outputs:", e)
        else:
          print("No tool outputs to submit.")

        if run.status == 'completed':
          messages = openai.beta.threads.messages.list(
            thread_id=thread.id
          )
          data = json.loads(messages.data[0].to_json());
          print (data["content"][0]["text"]["value"])
        else:
          print(run.status)

        return ""
    except Exception as e:
        print(f"An error occurred: {e}")
        return "I'm sorry, I couldn't process your request."
