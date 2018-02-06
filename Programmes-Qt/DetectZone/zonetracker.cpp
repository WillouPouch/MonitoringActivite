#include "zonetracker.h"

using namespace cv;

ZoneTracker::ZoneTracker(QObject *parent, bool debug) :
    QObject(parent)
    ,m_pos_patient("")
    ,m_debug(debug){

    moveToThread(&m_thread);
    connect(&m_thread, &QThread::started, this, &ZoneTracker::start_zone_tracking);
    m_thread.start();
}


void ZoneTracker::start_zone_tracking(){

    // initialisation de la m_capture video
    //m_cap = VideoCapture("D:\\PersoHDD\\zone_scen2.avi");
    m_cap = VideoCapture("http://192.168.197.21:80/mjpg/video.mjpg");
    if(!m_cap.isOpened()) return;

    HOGDescriptor hog;
    hog.setSVMDetector(HOGDescriptor::getDefaultPeopleDetector());

    while(1) { // boucle infinie
        Mat img;
        m_cap >> img; // recuperation de l'image courante
        resize(img, img, Size(), 0.25, 0.25); // réduction de l'image d'un facteur 4
        int largeur = img.cols;
        int hauteur = img.rows;
        if (!img.data) continue;

        /* Diférentes zones de la pièce */
        // Zone du salon
        /*Rect zone_salon = Rect(cvRound(largeur*0.30),cvRound(hauteur*0.695),cvRound(largeur*0.41),cvRound(hauteur*0.30));
        rectangle(img, zone_salon.tl(), zone_salon.br(), cv::Scalar(255,0,0), 2);*/
        // Zone de la cuisine
        /*Rect zone_cuis = Rect(cvRound(largeur*0.34),cvRound(hauteur*0.415),cvRound(largeur*0.155),cvRound(hauteur*0.21));
        rectangle(img, zone_cuis.tl(), zone_cuis.br(), cv::Scalar(0,0,255), 2);*/
        // Zone du lit
        /*Rect zone_lit = Rect(cvRound(largeur*0.52),cvRound(hauteur*0.415),cvRound(largeur*0.13),cvRound(hauteur*0.21));
        rectangle(img, zone_lit.tl(), zone_lit.br(), cv::Scalar(225,255,0), 2);*/

        /* Traitement HOG Detection */
        std::vector<Rect> found, found_filtered;
        hog.detectMultiScale(img, found, 0, Size(4,4), Size(16,16), 1.05, 2);
        size_t i, j;
        for (i=0; i<found.size(); i++) {
            Rect r = found[i];
            for (j=0; j<found.size(); j++)
                if (j!=i && (r & found[j])==r) break;
                if (j==found.size()) found_filtered.push_back(r);
        }
        for (i=0; i<found_filtered.size(); i++) {
            Rect r = found_filtered[i];
            r.x += cvRound(r.width*0.1);
            r.width = cvRound(r.width*0.8);
            r.y += cvRound(r.height*0.06);
            r.height = cvRound(r.height*0.9);

            // Détections parasites
            if((((r.x+r.width) > cvRound(largeur*0.28) && (r.x+r.width) < cvRound(largeur*0.31)) && ((r.y) > cvRound(hauteur*0.02) && (r.y) < cvRound(hauteur*0.074))) // detection non humaine
            || (((r.x+r.width) > cvRound(largeur*0.74) && (r.x+r.width) < cvRound(largeur*0.77)) && ((r.y) > cvRound(hauteur*0.02) && (r.y) < cvRound(hauteur*0.074))) // detection non humaine
            || (((r.y) > 0 && (r.y) < cvRound(hauteur*0.185)) && ((r.y+r.height) > cvRound(hauteur*0.695) && (r.y+r.height) < cvRound(hauteur*0.83)))) { // detection humaine trop grande (trsè souvent entre zones lit et salon)
                r = Rect(0,0,0,0);
            }

            // Vérification de la présence du patient dans l'une des 3 zones
                // SALON
            if(((r.x+r.width) > cvRound(largeur*0.30) && (r.x+r.width) < cvRound(largeur*0.71)) && ((r.y+r.height) > cvRound(hauteur*0.695) && (r.y+r.height) < cvRound(hauteur*0.995))) {
                m_pos_patient = "salon";
                emit zone_changed(m_pos_patient);
                //rectangle(img, r.tl(), r.br(), cv::Scalar(0,255,0), 2);
            }
                // CUISINE
            else if(((r.x+r.width) > cvRound(largeur*0.34) && (r.x+r.width) < cvRound(largeur*0.495)) && ((r.y+r.height) > cvRound(hauteur*0.415) && (r.y+r.height) < cvRound(hauteur*0.625))) {
                m_pos_patient = "cuisine";
                emit zone_changed(m_pos_patient);
                //rectangle(img, r.tl(), r.br(), cv::Scalar(0,255,0), 2);
            }
                // CHAMBRE
            else if(((r.x+r.width) > cvRound(largeur*0.52) && (r.x+r.width) < cvRound(largeur*0.65)) && ((r.y+r.height) > cvRound(hauteur*0.415) && (r.y+r.height) < cvRound(hauteur*0.625))) {
                m_pos_patient = "chambre";
                emit zone_changed(m_pos_patient);
                //rectangle(img, r.tl(), r.br(), cv::Scalar(0,255,0), 2);
            }
                // DETECTIONS PARASITES
            //else rectangle(img, r.tl(), r.br(), cv::Scalar(0,0,0), 2);
        }

        /* Affichage de l'image courante */
        //imshow("Zone Detection", img); // affichage de l'image courante
        //waitKey(1000/m_cap.get(CV_m_cap_PROP_FPS));
        //waitKey(10);
    }

    m_cap.release();
}

