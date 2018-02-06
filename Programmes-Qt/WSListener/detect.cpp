#include "detect.h"

Detect::Detect(QObject *parent, bool debug) :
    QObject(parent)
    ,m_debug(debug)
    ,m_liste_act(1)
    ,m_liste_as(1)
    ,m_network("http://192.168.199.1/monitoring_activite/"){

    QObject::connect(&m_liste_act, SIGNAL(act_json_ready(QJsonDocument)), this, SLOT(send_liste_act_json(QJsonDocument)));
    QObject::connect(&m_liste_as, SIGNAL(as_json_ready(QJsonDocument)), this, SLOT(send_liste_as_json(QJsonDocument)));
}

void Detect::send_liste_act_json(QJsonDocument json_doc){
    m_network.post_json("insert_json/insert_activite.php", json_doc);
}

void Detect::send_liste_as_json(QJsonDocument json_doc){
    m_network.post_json("insert_json/insert_anomalie_sonore.php", json_doc);
}


void Detect::slot_datareceived(QString message) {

    QJsonDocument json_doc = QJsonDocument::fromJson(message.toUtf8());
    QJsonObject json_obj;

    //Tester la conversion du message string en format JSON
    if(json_doc.isObject()){
        json_obj = json_doc.object();
    } else {
        if(m_debug) qDebug() << "[Detect::slot_datareceived] Wrong JSON !";
        return;
    }

    //Pour les anomalies sonores
    if( json_obj.contains("niveau") ){

        AnomalieSonore as(
            json_obj["niveau"].toDouble()
            ,QDateTime::fromString(json_obj["date_debut"].toString(),"dd/MM/yyyy HH:mm:ss")
            ,QDateTime::fromString(json_obj["date_fin"].toString(),"dd/MM/yyyy HH:mm:ss")
        );
        m_liste_as.append_custom(as);

    }
    //Pour les données les activités
    else if( json_obj.contains("type_activite") ){
        Activite act(
            QDateTime::fromString(json_obj["date_debut"].toString(),"dd/MM/yyyy HH:mm:ss")
            ,QDateTime::fromString(json_obj["date_fin"].toString(),"dd/MM/yyyy HH:mm:ss")
            ,json_obj["type_activite"].toString()
        );
        m_liste_act.append_custom(act);
    }
    else{
        if (m_debug) qDebug() << "[Detect::slot_datareceived] Objet JSON non reconnu !";
    }

}

