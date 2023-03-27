package Interfaces;

import javax.swing.*;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.DefaultTableModel;
import javax.swing.table.TableCellRenderer;
import javax.swing.table.TableColumnModel;
import javax.swing.table.TableModel;
import javax.swing.table.TableRowSorter;

import Model.Person;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Component;

public class ViewDoctors {
    JFrame frame;
    JPanel docPanel;
    JPanel buttonPanel;
    JButton button;
    JLabel lblTitle;
    JTable docTable;
    JScrollPane doctorsScrollPanel;

    public ViewDoctors(ArrayList<Person> doctorArray) {
        // create a JFrame, with a default size
        frame = new JFrame();
        frame.setTitle("Skin Consultation System");
        frame.setLayout(new BorderLayout(20, 15));

        docPanel = new JPanel();
        docPanel.setBounds(30, 30, 800, 400);
        docPanel.setBackground(Color.LIGHT_GRAY);
        docPanel.setLayout(new BorderLayout(20, 15));

        buttonPanel = new JPanel();
        buttonPanel.setBounds(30, 30, 800, 400);
        buttonPanel.setBackground(Color.LIGHT_GRAY);
        buttonPanel.setLayout(new BorderLayout(20, 15));

        lblTitle = new JLabel();
        lblTitle.setText("Doctors List");
        lblTitle.setBounds(30, 30, 800, 30);
        lblTitle.setHorizontalAlignment(SwingConstants.CENTER);
        lblTitle.setVerticalAlignment(SwingConstants.CENTER);
        lblTitle.setForeground(Color.BLACK);

        // create the doctors list
        String col[] = { "First Name", "Surname", "Phone Number", "Medical License No", "Specilization",
                "Consulatation" };
        DefaultTableModel tableModel = new DefaultTableModel(col, 0);
        for (int i = 0; i < doctorArray.size(); i++) {
            String name = doctorArray.get(i).getFirstName();
            String surname = doctorArray.get(i).getSurname();
            String phoneNumber = doctorArray.get(i).getPhoneNumber();
            String license = doctorArray.get(i).getMedicalLicenceNumber();
            String specialization = doctorArray.get(i).getSpecialisation();

            Object[] data = { name, surname, phoneNumber, license, specialization };

            tableModel.addRow(data);

        }

        docTable = new JTable(tableModel);
        docTable.setBounds(30, 30, 800, 300);
        docTable.getColumnModel().getColumn(5).setCellRenderer(new CheckAvailability());
        docTable.setAutoCreateRowSorter(true);

        // enable row sorter - name and surname
        TableRowSorter<TableModel> sorter = new TableRowSorter<TableModel>(docTable.getModel());
        docTable.setRowSorter(sorter);

        List<RowSorter.SortKey> sortKeys = new ArrayList<>(25);
        sortKeys.add(new RowSorter.SortKey(0, SortOrder.ASCENDING));
        sortKeys.add(new RowSorter.SortKey(1, SortOrder.ASCENDING));
        sorter.setSortKeys(sortKeys);

        // enable table row select - check availability
        docTable.getSelectionModel().addListSelectionListener(new ListSelectionListener() {
            public void valueChanged(ListSelectionEvent event) {
                // get the value of selected row
                System.out.println(docTable.getValueAt(docTable.getSelectedRow(), 3).toString());
            //    String license = docTable.getValueAt(docTable.getSelectedRow(), 3).toString();

                ConsultDoctor consultDoctor = new ConsultDoctor(doctorArray);
                consultDoctor.setVisible(true);
            }
        });

        doctorsScrollPanel = new JScrollPane(docTable);
        doctorsScrollPanel.setViewportView(docTable);
        resizeColumnWidth(docTable);

        docPanel.add(lblTitle, BorderLayout.NORTH);
        docPanel.add(doctorsScrollPanel, BorderLayout.CENTER);
        frame.add(docPanel, BorderLayout.CENTER);

        // create a button to add to the frame
        button = new JButton("View Availability");
        button.setBounds(15, 100, 200, 20);
        button.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                ConsultDoctor consultDoctor = new ConsultDoctor(doctorArray);
                consultDoctor.setVisible(true);
            }
        });

        frame.add(button, BorderLayout.SOUTH);   

        frame.setSize(800, 400);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLocationRelativeTo(null); // this method display the JFrame to center position of a screen

        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                // Dispose the window after the close button is clicked.
                System.exit(0);
            }
        });

    }

    class CheckAvailability extends DefaultTableCellRenderer {
        JLabel lblAvail = new JLabel();

        public JLabel getTableCellRendererComponent(JTable table, Object value, boolean isSelected,
                boolean hasFocus, int row, int column) {
            lblAvail.setText("Check Avilability");
            lblAvail.setBounds(2, 2, 200, 30);
            lblAvail.setForeground(Color.BLUE);
            return lblAvail;
        }
    }

    public void resizeColumnWidth(JTable table) {
        TableColumnModel columnModel = table.getColumnModel();

        for (int column = 0; column < table.getColumnCount(); column++) {
            int width = 100;

            for (int row = 0; row < table.getRowCount(); row++) {
                TableCellRenderer renderer = table.getCellRenderer(row, column);
                Component comp = table.prepareRenderer(renderer, row, column);
                width = Math.max(comp.getPreferredSize().width + 1, width);
            }

            if (width > 300)
                width = 300;

            columnModel.getColumn(column).setPreferredWidth(width);
        }
    }

    public void setVisible() {
        this.setVisible(true);
    }

    public void setVisible(boolean visible) {
        frame.setVisible(visible);
    }
}