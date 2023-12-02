import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from datetime import  datetime
import json


def send_mail(mailSubject, mailBody, email):

    #credentials
    smtpServer = 'smtp.gmail.com'
    port = 587
    sender = 'testingdormidosan@gmail.com'
    password = 'vgyy ajdb jozf cptp'


    message = MIMEMultipart()
    message['From'] = sender
    message['To'] = email
    message['Subject'] = mailSubject

    message.attach(MIMEText(mailBody, 'plain'))

    try:
        server = smtplib.SMTP(smtpServer, port)
        server.starttls()
        server.login(sender, password)
        server.sendmail(sender, email, message.as_string())
        response = "Email sent"
    except Exception as e:
        response = "Error: " + str(e)
    finally:
        server.quit()
    return response

def lambda_handler(event, context):

    mailSubject = event['mailSubject']
    email = event['email']
    mailBody =  event['mailBody']
    print(event)


    response = send_mail(mailSubject, mailBody, email)
    return {
        'statusCode': 200,
        'body': json.dumps('Mail sent') ,
        'event':str(event),
        'response':str(response),
        'time': str(datetime.today())
    }
