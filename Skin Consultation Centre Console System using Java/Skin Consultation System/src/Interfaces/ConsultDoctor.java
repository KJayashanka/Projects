package Interfaces;

import Model.Person;

import javax.swing.*;
import javax.swing.border.Border;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Map;
import java.util.TreeMap;

public class ConsultDoctor extends JFrame {
    JFrame frame = new JFrame("Check Availability");
    JLabel lblDoctor;
    JLabel lblSpec;
    JLabel lblHeading1;
    JButton button;
    JComboBox comboboxDoc;
    JComboBox comboboxSpec;

    JPanel labelHolder;
    JLabel[] lblAuto;

    String doc;
    String spec;

    ArrayList<Person> doctorsList;
    TreeMap<String, Integer> lableList;

    public ConsultDoctor(ArrayList<Person> doctorsList) {
        lblDoctor = new JLabel();
        lblSpec = new JLabel();
        lblHeading1 = new JLabel();

        this.doctorsList = doctorsList;

        // create specilizations list & doctors list
        String doctors[] = new String[doctorsList.size()];
        String specializations[] = new String[doctorsList.size()];
        findValues(doctors, specializations);

        comboboxDoc = new JComboBox(doctors);
        comboboxSpec = new JComboBox(specializations);

        comboboxDoc.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                doc = (String) comboboxDoc.getItemAt(comboboxDoc.getSelectedIndex());

                lableList = getDocAvailability(doc);

                if (lableList != null && !lableList.isEmpty()) {
                    // ArrayList<String> autoLableList = new ArrayList<>();
                    // ((Map<String, Integer>) lableList).entrySet().stream()
                    // .forEach(input -> autoLableList.add(input.getKey()));

                    ArrayList<String> lableList = new ArrayList<>();
                    lableList.add("one");
                    lableList.add("two");
                    lableList.add("three");
                    Object[] arrayResultRow = lableList.toArray();
                    // Object[] arrayResultRow = autoLableList.toArray();

                    labelHolder = new JPanel(new GridLayout(1, 3));
                    lblAuto = new JLabel[3];
                    for (int i = 0; i < 3; i++) {
                        lblAuto[i] = new JLabel(arrayResultRow[i].toString());
                        labelHolder.add(lblAuto[i]);
                    }

                    frame.add(labelHolder);

                }
            }
        });

        comboboxSpec.addActionListener(new ActionListener() {

            @Override
            public void actionPerformed(ActionEvent e) {
                spec = (String) comboboxSpec.getItemAt(comboboxSpec.getSelectedIndex());
            }

        });

        lblDoctor.setText("Select Doctor");
        lblSpec.setText("Select Specialization");
        lblHeading1.setText("Available on following days : Please pick a date to continue the booking");

        lableList = getDocAvailability(doc);

        if (lableList != null && !lableList.isEmpty()) {
            ArrayList<String> autoLableList = new ArrayList<>();
             ((Map<String, Integer>) lableList).entrySet().stream()
             .forEach(input -> autoLableList.add(input.getKey()));

            Collections.sort(autoLableList);

            // ArrayList<String> lableList = new ArrayList<>();
            // lableList.add("one");
            // lableList.add("two");
            // lableList.add("three");
            // Object[] arrayResultRow = lableList.toArray();
            Object[] arrayResultRow = autoLableList.toArray();
            Border greenBorder = BorderFactory.createLineBorder(Color.BLACK);
            Border redBorder = BorderFactory.createLineBorder(Color.RED);

            labelHolder = new JPanel(new GridLayout(1, 10));
            lblAuto = new JLabel[10];
            for (int i = 0; i < 10; i++) {
                lblAuto[i] = new JLabel(arrayResultRow[i].toString());
                lblAuto[i].setPreferredSize(new Dimension(40, 40));
                lblAuto[i].setBorder(greenBorder);
                lblAuto[i].setFont(new Font("Arial", Font.BOLD, 16));
                labelHolder.add(lblAuto[i]);
            }

            labelHolder.setPreferredSize(new Dimension(500,40));
        }

        button = new JButton("Add Patient");
        button.setBounds(15, 100, 200, 20);
        button.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                AddPatient addPatient = new AddPatient();
                addPatient.setVisible(true);
            }
        });

        frame.setSize(600, 400);
        frame.setLocationRelativeTo(null);
        frame.setLayout(new FlowLayout());

        frame.add(lblSpec);
        frame.add(comboboxSpec);
        frame.add(lblDoctor);
        frame.add(comboboxDoc);
        frame.add(lblHeading1);
        frame.add(labelHolder);
        frame.add(button);

        frame.setVisible(true);
    }

    public void findValues(String[] doctors, String[] specializations) {
        for (int i = 0; i < doctorsList.size(); i++) {
            Person person = doctorsList.get(i);
            doc = person.getFirstName() + " " + person.getSurname();
            spec = person.getSpecialisation();
            
            doctors[i] = doc;
            specializations[i] = spec;
        }
    }

    public TreeMap<String, Integer> getDocAvailability(String name) {
        for (int i = 0; i < doctorsList.size(); i++) {
            Person person = doctorsList.get(i);
            if (name.equalsIgnoreCase(person.getFirstName() + " " + person.getSurname())) {
                return person.getAvailability();
            }
        }
        return null;
    }
}
