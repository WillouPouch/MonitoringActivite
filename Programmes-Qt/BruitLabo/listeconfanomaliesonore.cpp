#include "listeconfanomaliesonore.h"


ListeConfAnomalieSonore::ListeConfAnomalieSonore(QObject *parent, bool debug) :
    QObject(parent)
    ,m_network("http://192.168.199.1/monitoring_activite/")
    ,m_debug(debug){

    connect(&m_network, SIGNAL(data_received(QString)), this, SLOT(reload_vector_from_json(QString)));

    m_timer = new QTimer(this);
    connect(m_timer, SIGNAL(timeout()), this, SLOT(timer_update()));
    m_timer->start();
}



void ListeConfAnomalieSonore::timer_update(){
    if(m_timer->interval()==0) m_timer->setInterval(5000);
    m_network.get_json("select_json/select_conf_anomalie_sonore.php");
}



void ListeConfAnomalieSonore::reload_vector_from_json(QString json){

    if(m_debug) qDebug() << "[ListeConfAnomalieSonore::reload_vector_from_json] json  :  " << json;

    m_vector.clear();

    QJsonDocument json_doc = QJsonDocument::fromJson(json.toUtf8());
    QJsonObject json_obj;

    //Tester la conversion du message string en format JSON
    if(json_doc.isObject()){
        json_obj = json_doc.object();
    } else {
        if(m_debug) qDebug() << "[ListeConfAnomalieSonore::reload_vector_from_json] Wrong JSON !";
        return;
    }

    QJsonArray json_cas_array = json_obj["DATA"].toArray();

    foreach (const QJsonValue &value, json_cas_array) {
        QJsonObject obj = value.toObject();

        QTime heure_debut = QTime::fromString(obj["heure_debut"].toString(),"hh:mm:ss");
        QTime heure_fin = QTime::fromString(obj["heure_fin"].toString(),"hh:mm:ss");
        /*if (heure_debut == QTime::fromString("00:00:00", "hh:mm:ss")) heure_debut = QTime::fromString("00:00:01", "hh:mm:ss");
        if (heure_fin == QTime::fromString("00:00:00", "hh:mm:ss")) heure_fin = QTime::fromString("23:59:59", "hh:mm:ss");*/

        ConfAnomalieSonore cas(
            heure_debut
            ,heure_fin
            ,obj["seuil"].toString().toInt()
            ,obj["duree"].toString().toInt()
            ,obj["id_conf_as"].toString().toInt()
        );
        m_vector.append(cas);
    }
}


const ConfAnomalieSonore* ListeConfAnomalieSonore::get_cas_time(QTime time){

    foreach (const ConfAnomalieSonore &cas, m_vector) {

        QTime heure_fin;
        if(cas.get_heure_fin().toString()=="00:00:00") heure_fin = QTime::fromString("23:59:59","hh:mm:ss");
        else heure_fin = cas.get_heure_fin();

        if(cas.get_heure_debut() <= time && time <= heure_fin) return &cas;
    }
    return Q_NULLPTR;
}
